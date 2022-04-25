<?php
defined('TYPO3') || die('Access denied.');

call_user_func(
    function ($extKey) {
        // @todo TYPO3v11 removal: remove toolbar items registration via ext_localconf.php
        $GLOBALS['TYPO3_CONF_VARS']['BE']['toolbarItems'][1502345259] = \JosefGlatz\BeuserFastswitch\Hooks\Backend\Toolbar\BackendUserPreviewToolbarItem::class;
    },
    'beuser_fastswitch'
);
