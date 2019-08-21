<?php declare(strict_types=1);

namespace JosefGlatz\BeuserFastswitch\Hooks\Backend\Toolbar;

use JosefGlatz\BeuserFastswitch\Domain\Repository\BackendUserRepository;
use JosefGlatz\BeuserFastswitch\Service\VersionService;
use TYPO3\CMS\Backend\Toolbar\ToolbarItemInterface;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Exception\InvalidExtensionNameException;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Fluid\View\StandaloneView;

/**
 * Main functionality to render a list of backend users to which it is possible to switch as an admin
 */
class BackendUserPreviewToolbarItem implements ToolbarItemInterface
{
    /**
     * @var QueryResultInterface|null
     */
    protected $availableUsers = null;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->loadAvailableBeUsers();
        $this->getPageRenderer()->loadRequireJsModule('TYPO3/CMS/BeuserFastswitch/BeuserFastswitch');
    }

    /**
     * Checks whether
     * - the user has access to this toolbar item
     * - the toolbar item isn't disabled via UserTSConfig
     *
     * @return bool TRUE if user has access and toolbarItem is enabled, FALSE if not
     */
    public function checkAccess(): bool
    {
        $conf = $this->getBackendUserAuthentication()->getTSConfig()['options.']['backendToolbarItem.']['beUserFastwitch.']['disabled'];
        return (int)$conf !== 1 && $this->getBackendUserAuthentication()->isAdmin() && !$this->getBackendUserAuthentication()->user['ses_backuserid'];
    }

    /**
     * Loads all eligible backend users
     */
    public function loadAvailableBeUsers(): void
    {
        if ($this->checkAccess()) {
            $this->availableUsers = $this->getBackendUserRows();
        }
    }

    /**
     * Render toolbar icon via Fluid
     *
     * @return string HTML
     * @throws InvalidExtensionNameException
     */
    public function getItem(): string
    {
        return $this->getFluidTemplateObject('ToolbarItem.html')->render();
    }

    /**
     * Render drop down via Fluid
     *
     * @return string HTML
     * @throws InvalidExtensionNameException
     */
    public function getDropDown(): string
    {
        $view = $this->getFluidTemplateObject('DropDown.html');
        $view->assignMultiple([
            'users' => $this->availableUsers,
            'isVersion8' => VersionService::isVersion8(),
            'isVersion10' => VersionService::isVersion10(),
        ]);
        return $view->render();
    }

    /**
     * Additional attributes of toolbar item
     *
     * @return array List item HTML attibutes
     */
    public function getAdditionalAttributes(): array
    {
        return [
            'class' => 'tx-beuser-fastswitch'
        ];
    }

    /**
     * This item has a drop down
     *
     * @return bool
     */
    public function hasDropDown(): bool
    {
        return true;
    }

    /**
     * Position relative to toolbar items
     *
     * @return int
     */
    public function getIndex(): int
    {
        return 10;
    }

    /**
     * Returns the current BE user.
     *
     * @return BackendUserAuthentication
     */
    protected function getBackendUserAuthentication(): BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }

    /**
     * Returns a new standalone view, shorthand function
     * @TODO: Add extConf option to add additional fluid relevant paths
     *
     * @param string $filename Which templateFile should be used.
     * @return StandaloneView
     * @throws InvalidExtensionNameException
     */
    protected function getFluidTemplateObject(string $filename): StandaloneView
    {
        $view = GeneralUtility::makeInstance(StandaloneView::class);

        $view->setLayoutRootPaths([
            'EXT:beuser_fastswitch/Resources/Private/Layouts',
        ]);
        $view->setPartialRootPaths([
            'EXT:backend/Resources/Private/Partials/ToolbarItems',
            'EXT:beuser_fastswitch/Resources/Private/Partials'
        ]);
        $view->setTemplateRootPaths(['EXT:beuser_fastswitch/Resources/Private/Templates']);

        $view->setTemplate($filename);

        $view->getRequest()->setControllerExtensionName('BeuserFastswitch');
        return $view;
    }

    /**
     * Retrieve available backend users
     * @TODO: Streamline method (do not combine raw result with object if possible)
     * @TODO: Make user configurable (groups, types, username patterns)
     * @TODO: Check if there's another way to access the internal user array of actual backend user IF using internals is the wrong way
     *
     * @return QueryResultInterface|null
     * @throws InvalidQueryException
     */
    protected function getBackendUserRows(): ?QueryResultInterface
    {
        /** @var $extbaseObjectManager \TYPO3\CMS\Extbase\Object\ObjectManager */
        $extbaseObjectManager = GeneralUtility::makeInstance(ObjectManager::class);
        /** @var $backendUserRepository BackendUserRepository */
        $backendUserRepository = $extbaseObjectManager->get(BackendUserRepository::class);

        $rows = $backendUserRepository->findNonAdmins();

        if ($rows instanceof QueryResultInterface) {
            return $rows;
        }

        return null;
    }

    /**
     * Returns current PageRenderer
     *
     * @return PageRenderer
     */
    protected function getPageRenderer(): PageRenderer
    {
        return GeneralUtility::makeInstance(PageRenderer::class);
    }
}
