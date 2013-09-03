<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "varnish".
 *
 * Auto generated 14-08-2013 11:46
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Varnish Connector',
	'description' => 'This extension is managed on GitHub. Feel free to get in touch at https://github.com/snowflakech/typo3-varnish/',
	'category' => 'misc',
	'shy' => 0,
	'version' => '1.0.4',
	'constraints' => array(
		'depends' => array(
			'php' => '5.3.0-0.0.0',
			'typo3' => '4.5.0-6.2.99',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'state' => 'stable',
	'author' => 'Andri Steiner',
	'author_email' => 'varnish@snowflake.ch',
	'author_company' => 'snowflake',
	'_md5_values_when_last_written' => 'a:15:{s:16:"ext_autoload.php";s:4:"96e7";s:21:"ext_conf_template.txt";s:4:"a3be";s:12:"ext_icon.gif";s:4:"a993";s:17:"ext_localconf.php";s:4:"cf97";s:13:"locallang.xml";s:4:"4c69";s:10:"README.rst";s:4:"f6be";s:39:"classes/class.tx_varnish_controller.php";s:4:"edc9";s:33:"classes/class.tx_varnish_http.php";s:4:"1e50";s:45:"classes/Hooks/class.tx_varnish_hooks_ajax.php";s:4:"82d2";s:55:"classes/Hooks/class.tx_varnish_hooks_clearcachemenu.php";s:4:"d481";s:48:"classes/Hooks/class.tx_varnish_hooks_tcemain.php";s:4:"9abd";s:49:"classes/Hooks/class.tx_varnish_hooks_tslib_fe.php";s:4:"df9c";s:53:"classes/Utilities/class.tx_varnish_generalutility.php";s:4:"fa98";s:15:"res/default.vcl";s:4:"0d1a";s:16:"static/setup.txt";s:4:"69d1";}',
);

?>
