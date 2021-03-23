<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Slack notifications for TYPO3',
    'description' => 'Send notifications from TYPO3 to slack',
    'version' => '0.1.0',
    'state' => 'alpha',
    'category' => 'plugin',
    'author' => 'Micha Grandel',
    'author_email' => 'micha@plywoodpirate.de',
    'constraints' => [
        'depends' => [
            'typo3' => '9.5.0-10.4.99',
        ],
        'conflicts' => [
        ],
        'suggests' => [
        ],
    ],
];
