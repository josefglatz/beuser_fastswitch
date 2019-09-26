<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'Fast Backend User Switch',
    'description' => 'Fast backend user switch for TYPO3 CMS administrator users.',
    'category' => 'be',
    'author' => 'Josef Glatz',
    'author_email' => 'josefglatz@gmail.com',
    'author_company' => 'https://www.josefglatz.at',
    'state' => 'stable',
    'clearCacheOnLoad' => true,
    'version' => '3.2.0',
    'constraints' => [
        'depends' => [
            'typo3' => '8.7.0-10.4.99',
            'php' => '7.2.0-0.0.0'
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
