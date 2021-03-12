<?php
/***************************************************************
 * Extension Manager/Repository config file for ext "varnish".
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array (
    'title' => 'Varnish Connector',
    'description' => 'Make your TYPO3 website blazing fast and stable for loads of simultaneous visitors. Seamless integration of TYPO3 cached pages into Varnish.',
    'category' => 'misc',
    'shy' => 0,
    'version' => '4.0.2',
    'constraints' => array (
        'depends' => array (
            'php' => '7.2.0-7.4.99',
            'typo3' => '9.5.0-10.4.99',
        ),
        'conflicts' => array (),
        'suggests' => array (),
    ),
    'state' => 'stable',
    'author' => 'Andri Steiner',
    'author_email' => 'team@opsone.ch',
    'author_company' => 'Ops One AG',
);
