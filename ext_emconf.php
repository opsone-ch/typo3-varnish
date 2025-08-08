<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "varnish".
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array (
    'title' => 'Varnish for TYPO3',
    'description' => 'Seamless integration of TYPO3 cached pages into Varnish.' .
                     'Blazing fast response times for loads of simultaneous visitors.',
    'category' => 'misc',
    'version' => '7.1.0',
    'constraints' => array (
        'depends' => array (
            'php' => '8.1.0-8.4.99',
            'typo3' => '12.4.0-13.5.99',
        ),
        'conflicts' => array (),
        'suggests' => array (),
    ),
    'state' => 'stable',
    'author' => 'Andri Steiner',
    'author_email' => 'team@opsone.ch',
    'author_company' => 'Ops One AG',
);
