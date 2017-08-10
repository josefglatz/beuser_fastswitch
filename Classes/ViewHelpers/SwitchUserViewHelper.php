<?php
namespace JosefGlatz\BeuserFastswitch\ViewHelpers;

use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Beuser\Domain\Model\BackendUser;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * Displays 'SwitchUser' link with sprite icon to change current backend user to target backendUser
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
        parent::initializeArguments();
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
        $backendUser = $arguments['backendUser'];
        $class = $arguments['class'];
        $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
        $iconFactory = GeneralUtility::makeInstance(IconFactory::class);
        if ($backendUser->getUid() == $GLOBALS['BE_USER']->user['uid'] || !$backendUser->isActive() || $GLOBALS['BE_USER']->user['ses_backuserid']) {
            return '<span class="btn btn-default disabled">' . $iconFactory->getIcon('empty-empty', Icon::SIZE_SMALL)->render() . '</span>';
        }
        $title = LocalizationUtility::translate('toolbar.beuser.fastswitch.dropdown.user.btn.switch', 'beuser_fastswitch');
        return '<a class="' . htmlspecialchars($class) . '" href="' .
            htmlspecialchars($uriBuilder->buildUriFromModule('system_BeuserTxBeuser', ['SwitchUser' => $backendUser->getUid()])) .
            '" target="_top" title="' . htmlspecialchars($title) . '">' .
            $iconFactory->getIcon('actions-system-backend-user-switch', Icon::SIZE_SMALL)->render('inline') . '</a>';
    }
}
