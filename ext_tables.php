<?php
defined('TYPO3') or die();
call_user_func(
    static function ($extKey) {
        // Only backend relevant stuff
        $GLOBALS['TBE_STYLES']['skins'][$extKey] = [
            'name' => $extKey,
            'stylesheetDirectories' => [
                'css' => 'EXT:' . $extKey . '/Resources/Public/Css/',
            ],
        ];
    },
    'beuser_fastswitch'
);
