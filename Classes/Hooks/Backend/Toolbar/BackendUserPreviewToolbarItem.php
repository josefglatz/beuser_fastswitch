<?php declare(strict_types=1);

namespace JosefGlatz\BeuserFastswitch\Hooks\Backend\Toolbar;

use JosefGlatz\BeuserFastswitch\Domain\Repository\BackendUserRepository;
use TYPO3\CMS\Backend\Toolbar\ToolbarItemInterface;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Exception\InvalidExtensionNameException;
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Fluid\View\StandaloneView;

/**
 * Main functionality to render a list of backend users to which it is possible to switch as an admin
 */
class BackendUserPreviewToolbarItem implements ToolbarItemInterface
{
    /**
     * @var BackendUserRepository
     */
    private $backendUserRepository;

    /**
     * @var PageRenderer
     */
    protected PageRenderer $pageRenderer;

    /**
     * @var QueryResultInterface|null
     */
    protected $availableUsers = null;

    /**
     * Constructor
     */
    public function __construct(
        BackendUserRepository $backendUserRepository,
        PageRenderer $pageRenderer
        ) {
            $this->backendUserRepository = $backendUserRepository;
            $this->loadAvailableBeUsers();
            $pageRenderer->loadRequireJsModule('TYPO3/CMS/BeuserFastswitch/BeuserFastswitch');
            $this->pageRenderer = $pageRenderer;
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
     * Checks whether
     * - the user has access to this toolbar item
     * - the toolbar item isn't disabled via UserTSConfig
     *
     * @return bool TRUE if user has access and toolbarItem is enabled, FALSE if not
     */
    public function checkAccess(): bool
    {
        $backendUser = $this->getBackendUser();
        $conf = $this->getBackendUserAuthentication()->getTSConfig()['options.']['backendToolbarItem.']['beUserFastwitch.']['disabled'] ?? false;

        return (int)$conf !== 1 && $this->getBackendUserAuthentication()->isAdmin() && !(int)$backendUser->getOriginalUserIdWhenInSwitchUserMode();
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
     *
     * @param string $filename Which templateFile should be used.
     * @return StandaloneView
     */
    protected function getFluidTemplateObject(string $filename): StandaloneView
    {
        $view = GeneralUtility::makeInstance(StandaloneView::class);

        $view->setPartialRootPaths([
            'EXT:backend/Resources/Private/Partials/ToolbarItems',
            'EXT:beuser_fastswitch/Resources/Private/Partials'
        ]);
        $view->setTemplateRootPaths(['EXT:beuser_fastswitch/Resources/Private/Templates']);

        $view->setTemplate($filename);

        // @todo: re-think setting the request in StandaloneView via $GLOBALS['TYPO3_REQUEST']
        $view->setRequest($GLOBALS['TYPO3_REQUEST']);
        return $view;
    }

    /**
     * Retrieve available backend users
     *
     * @return QueryResultInterface|null
     * @throws InvalidQueryException
     */
    protected function getBackendUserRows(): ?QueryResultInterface
    {
        $rows = $this->backendUserRepository->findNonAdmins();

        if ($rows instanceof QueryResultInterface) {
            return $rows;
        }

        return null;
    }

    protected function getBackendUser(): BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }
}
