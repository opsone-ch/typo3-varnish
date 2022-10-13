<?php
/***************************************************************
 * Extension Manager/Repository config file for ext "varnish".
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array (
    'title' => 'Varnish for TYPO3',
    'description' => 'Make your TYPO3 website blazing fast and stable for loads of simultaneous visitors. Seamless integration of TYPO3 cached pages into Varnish.',
    'category' => 'misc',
    'shy' => 0,
    'version' => '5.0.1',
    'constraints' => array (
        'depends' => array (
            'php' => '7.4.0-8.1.99',
            'typo3' => '10.4.11-11.5.99',
        ),
        'conflicts' => array (),
        'suggests' => array (),
    ),
    'state' => 'stable',
    'author' => 'Andri Steiner',
    'author_email' => 'team@opsone.ch',
    'author_company' => 'Ops One AG',
);
