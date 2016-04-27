<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2012  Andri Steiner  <support@snowflake.ch>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

switch (TYPO3_MODE) {
	case 'FE':
		// Typoscript
		TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript('varnish', 'setup', '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:varnish/Configuration/TypoScript/setup.txt">', 43);

		// Hooks
		$TYPO3_CONF_VARS['SC_OPTIONS']['tslib/class.tslib_fe.php']['isOutputting'][] = 'Snowflake\\Varnish\\Hooks\\Frontend->sendHeader';
		break;
	case 'BE':
		// Hooks
		\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerAjaxHandler (
				'tx_varnish::banAll',
				'Snowflake\\Varnish\\Hooks\\Ajax->banAll',
				false
		);
		$TYPO3_CONF_VARS['SC_OPTIONS']['additionalBackendItems']['cacheActions'][] = 'Snowflake\\Varnish\\Hooks\\ClearCacheMenu';
		$TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearCachePostProc'][] = 'Snowflake\\Varnish\\Hooks\\DataHandler->clearCachePostProc';
		break;
}

?>
