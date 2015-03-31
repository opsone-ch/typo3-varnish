<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "varnish".
 *
 * Auto generated 31-03-2015 14:34
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
	'version' => '1.0.7',
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
	'_md5_values_when_last_written' => 'a:16:{s:16:"ext_autoload.php";s:4:"a0ec";s:21:"ext_conf_template.txt";s:4:"d213";s:12:"ext_icon.gif";s:4:"a993";s:17:"ext_localconf.php";s:4:"975b";s:13:"locallang.xml";s:4:"1b38";s:10:"README.rst";s:4:"f6be";s:39:"classes/class.tx_varnish_controller.php";s:4:"92ff";s:33:"classes/class.tx_varnish_http.php";s:4:"9bf8";s:45:"classes/Hooks/class.tx_varnish_hooks_ajax.php";s:4:"82d2";s:55:"classes/Hooks/class.tx_varnish_hooks_clearcachemenu.php";s:4:"95b8";s:48:"classes/Hooks/class.tx_varnish_hooks_tcemain.php";s:4:"9abd";s:49:"classes/Hooks/class.tx_varnish_hooks_tslib_fe.php";s:4:"6e1a";s:53:"classes/Utilities/class.tx_varnish_generalutility.php";s:4:"0276";s:17:"res/default-4.vcl";s:4:"e648";s:15:"res/default.vcl";s:4:"4b55";s:16:"static/setup.txt";s:4:"69d1";}',
);

?>