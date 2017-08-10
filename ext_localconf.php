<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function ($extKey) {
        // Only backend relevant stuff
        if (TYPO3_MODE === 'BE') {

            // Extend TYPO3 toolbar: Fast backend user switch
            $GLOBALS['TYPO3_CONF_VARS']['BE']['toolbarItems'][1502345259] = \JosefGlatz\BeuserFastswitch\Hooks\Backend\Toolbar\BackendUserPreviewToolbarItem::class;
        }
    },
    $_EXTKEY
);
