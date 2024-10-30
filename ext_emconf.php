<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'Fast Backend User Switch',
    'description' => 'Fast backend user switch for TYPO3 CMS administrator users.',
    'category' => 'be',
    'author' => 'Josef Glatz',
    'author_email' => 'typo3@josefglatz.at',
    'author_company' => 'https://www.josefglatz.at',
    'state' => 'stable',
    'clearCacheOnLoad' => true,
    'version' => '6.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '13.4.0-13.4.99',
            'php' => '8.2.0-8.3.99'
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'JosefGlatz\\BeuserFastswitch\\' => 'Classes',
        ],
    ],
    'autoload-dev' => [
        'psr-4' => [
            'JosefGlatz\\BeuserFastswitch\\Tests\\' => 'Tests',
        ],
    ],
];
