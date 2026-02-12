<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "varnish".
 ***************************************************************/

$EM_CONF[$_EXTKEY] =  [
    'title' => 'Varnish for TYPO3',
    'description' => 'Seamless integration of TYPO3 cached pages into Varnish.' .
                     'Blazing fast response times for loads of simultaneous visitors.',
    'category' => 'misc',
    'version' => '8.0.0-dev',
    'constraints' =>  [
        'depends' =>  [
            'php' => '8.2.0-8.4.99',
            'typo3' => '13.4.0-14.3.99',
        ],
        'conflicts' =>  [],
        'suggests' =>  [],
    ],
    'state' => 'stable',
    'author' => 'Andri Steiner',
    'author_email' => 'team@opsone.ch',
    'author_company' => 'Ops One AG',
];
