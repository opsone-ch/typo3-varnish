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

if(!defined('TYPO3_MODE')) die ('Access denied.');

switch(TYPO3_MODE) {
	case 'FE':
		// Typoscript
		t3lib_extMgm::addTypoScriptSetup('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:varnish/static/setup.txt">');

		// Hooks
		$TYPO3_CONF_VARS['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-output'][] = 'EXT:varnish/classes/Hooks/class.tx_varnish_hooks_tslib_fe.php:tx_varnish_hooks_tslib_fe->sendHeader';
		break;
	case 'BE':
		// Hooks
		$TYPO3_CONF_VARS['BE']['AJAX']['tx_varnish::banAll'] = 'EXT:varnish/classes/Hooks/class.tx_varnish_hooks_ajax.php:tx_varnish_hooks_ajax->banAll';
		$TYPO3_CONF_VARS['SC_OPTIONS']['additionalBackendItems']['cacheActions'][] = 'EXT:varnish/classes/Hooks/class.tx_varnish_hooks_clearcachemenu.php:tx_varnish_hooks_clearcachemenu';
		$TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearCachePostProc'][] = 'EXT:varnish/classes/Hooks/class.tx_varnish_hooks_tcemain.php:tx_varnish_hooks_tcemain->clearCachePostProc';

		break;
}

?>