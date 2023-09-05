<?php declare(strict_types=1);

namespace JosefGlatz\BeuserFastswitch\Backend\Toolbar;

use JosefGlatz\BeuserFastswitch\Domain\Repository\BackendUserRepository;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\Toolbar\RequestAwareToolbarItemInterface;
use TYPO3\CMS\Backend\Toolbar\ToolbarItemInterface;
use TYPO3\CMS\Backend\View\BackendViewFactory;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Extbase\Mvc\Exception\InvalidExtensionNameException;
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

/**
 * Main functionality to render a list of backend users to which it is possible to switch as an admin
 */
class BackendUserPreviewToolbarItem implements ToolbarItemInterface, RequestAwareToolbarItemInterface
{
    /**
     * @var BackendUserRepository
     */
    private $backendUserRepository;

    /**
     * @var QueryResultInterface|null
     */
    protected $availableUsers = null;
    private ServerRequestInterface $request;

    /**
     * Constructor
     */
    public function __construct(
        BackendUserRepository $backendUserRepository,
        private readonly BackendViewFactory $backendViewFactory,
        ) {
            $this->backendUserRepository = $backendUserRepository;
            $this->loadAvailableBeUsers();
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

    public function setRequest(ServerRequestInterface $request): void
    {
        $this->request = $request;
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
     */
    public function getItem(): string
    {
        $view = $this->backendViewFactory->create($this->request, ['josefglatz/beuser-fastswitch']);

        return $view->render('ToolbarItem.html');
    }

    /**
     * Render drop down via Fluid
     *
     * @return string HTML
     * @throws InvalidExtensionNameException
     */
    public function getDropDown(): string
    {
        $view = $this->backendViewFactory->create($this->request,  ['josefglatz/beuser-fastswitch']);
        $view->assignMultiple([
            'users' => $this->availableUsers,
        ]);

        return $view->render('DropDown.html');
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
     * This item has always a dropdown
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
