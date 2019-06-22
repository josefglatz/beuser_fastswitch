
<?php
defined('TYPO3_MODE') || die('Access denied.');
call_user_func(
    function ($extKey) {
        // Only backend relevant stuff
        $GLOBALS['TBE_STYLES']['skins'][$extKey] = [
            'name' => $extKey,
            'stylesheetDirectories' => [
                'css' => 'EXT:beuser_fastswitch/Resources/Public/Css/',
            ],
        ];
    },
    'beuser_fastswitch'
);