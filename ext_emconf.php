<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'Fast Backend User Switch',
    'description' => 'Fast backend user switch for TYPO3 CMS administrator users.',
    'category' => 'be',
    'author' => 'Josef Glatz',
    'author_email' => 'jousch@gmail.com',
    'author_company' => 'http://jousch.com',
    'state' => 'stable',
    'uploadfolder' => '0',
    'createDirs' => '',
    'clearCacheOnLoad' => 1,
    'version' => '0.1.0',
    'constraints' =>
        [
            'depends' =>
                [
                    'typo3' => '8.7.1-8.7.99',
                ],
            'conflicts' =>
                [
                ],
            'suggests' =>
                [
                ],
        ],
    'autoload' =>
        [
            'psr-4' =>
                [
                    'JosefGlatz\\BeuserFastswitch\\' => 'Classes',
                ],
        ],
    'autoload-dev' =>
        [
            'psr-4' =>
                [
                    'JosefGlatz\\BeuserFastswitch\\Tests\\' => 'Tests',
                ],
        ],
];
