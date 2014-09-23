<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2013 snowflake productions gmbh <support@snowflake.ch>
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
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * Hint: use extdeveval to insert/update function index above.
 */



/**
 * Helper class for varnish
 *
 * @author	Sascha Hepp <support@snowflake.ch>
 * @package	TYPO3
 * @subpackage	tx_varnish
 */
class tx_varnish_generalutility {

	static $extConf;


	/**
	 * Load extension configuration
	 */
	protected static function loadExtConf() {
		// load Extension Configuration
		if(!isset(self::$extConf)) {
			self::$extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['varnish']);
		}
	}


	/**
	 * Devlog if enabled
	 *
	 * @param string $functionName
	 * @param string $additionalData
	 */
	public static function devLog($functionName, $additionalData = '') {
		self::loadExtConf();
		if(self::$extConf['enableDevLog']) {
			t3lib_div::devLog($functionName, 'varnish', 0, $additionalData);
		}
	}

	/**
	 * Returns HMAC of the sitename
	 *
	 * @return mixed
	 */
	public static function getSitename() {
		return t3lib_div::hmac($GLOBALS['TYPO3_CONF_VARS']['SYS']['sitename']);
	}
}

global $TYPO3_CONF_VARS;
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/varnish/classes/Utilities/class.tx_varnish_generalutility.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/varnish/classes/Utilities/class.tx_varnish_generalutility.php']);
}
?>