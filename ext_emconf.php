<?php
/***************************************************************
 * Extension Manager/Repository config file for ext "varnish".
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array (
    'title' => 'Varnish Connector',
    'description' => 'This extension is managed on GitHub. Feel free to get in touch at https://github.com/snowflakeOps/typo3-varnish/',
    'category' => 'misc',
    'shy' => 0,
    'version' => '2.1.2',
    'constraints' => array (
        'depends' => array (
            'php' => '5.6.0-7.1.99',
            'typo3' => '7.6.0-8.7.99',
        ),
        'conflicts' => array (),
        'suggests' => array (),
    ),
    'state' => 'stable',
    'author' => 'Andri Steiner',
    'author_email' => 'team@snowflakeops.ch',
    'author_company' => 'snowflake Ops AG',
);
