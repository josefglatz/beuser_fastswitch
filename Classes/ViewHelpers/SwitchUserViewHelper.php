<?php
namespace JosefGlatz\BeuserFastswitch\ViewHelpers;

use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Beuser\Domain\Model\BackendUser;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * Displays 'SwitchUser' link with sprite icon to change current backend user to target backendUser
 *
 * This ViewHelper is basically a clone of \TYPO3\CMS\Beuser\ViewHelpers\SwitchUserViewHelper.
 * As the mentioned core VH is marked as internal, the modified version was added to this
 * extension as an own standalone VH.
 */
class SwitchUserViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    /**
     * As this ViewHelper renders HTML, the output must not be escaped.
     *
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * Initializes the arguments
     */
    public function initializeArguments()
    {
        $this->registerArgument('backendUser', BackendUser::class, 'Target backendUser to switch active session to', true);
        $this->registerArgument('class', 'string', 'Css class(es) for <a\/> tag', false);
    }

    /**
     * Render link with sprite icon to change current backend user to target
     *
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     *
     * @return string
     */
    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
        $targetUser = $arguments['backendUser'];
        $currentUser = self::getBackendUserAuthentication();
        $iconFactory = GeneralUtility::makeInstance(IconFactory::class);

        if ((int)$targetUser->getUid() === (int)($currentUser->user[$currentUser->userid_column] ?? 0)
            || !$targetUser->isActive()
            || !$currentUser->isAdmin()
            || $currentUser->getOriginalUserIdWhenInSwitchUserMode() !== null
        ) {
            return '<span class="btn btn-default disabled">' . $iconFactory->getIcon('empty-empty', Icon::SIZE_SMALL)->render() . '</span>';
        }

        return '
            <typo3-backend-switch-user targetUser="' . htmlspecialchars((string)$targetUser->getUid()) . '">
                <button type="button" class="' . $arguments['class'] . '" title="' . htmlspecialchars(LocalizationUtility::translate('toolbar.beuser.fastswitch.dropdown.user.btn.switch', 'beuser_fastswitch') ?? '') . '">'
            . $iconFactory->getIcon('actions-system-backend-user-switch', Icon::SIZE_SMALL)->render() .
            '</button>
            </typo3-switch-user-button>';
    }

    protected static function getBackendUserAuthentication(): BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }
}
