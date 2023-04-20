<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "varnish".
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array (
    'title' => 'Varnish for TYPO3',
    'description' => 'Seamless integration of TYPO3 cached pages into Varnish.' .
                     'Blazing fast response times for loads of simultaneous visitors.',
    'category' => 'misc',
    'version' => '6.0.0',
    'constraints' => array (
        'depends' => array (
            'php' => '7.4.0-8.2.99',
            'typo3' => '11.5.0-12.5.99',
        ),
        'conflicts' => array (),
        'suggests' => array (),
    ),
    'state' => 'stable',
    'author' => 'Andri Steiner',
    'author_email' => 'team@opsone.ch',
    'author_company' => 'Ops One AG',
);
