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
    'version' => '5.0.2',
    'constraints' => [
        'depends' => [
            'typo3' => '12.4.5-12.4.99',
            'php' => '8.1.0-8.2.99'
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
