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


/**
 * This class contains required hooks which are called by TYPO3
 *
 * @author	Andri Steiner  <support@snowflake.ch>
 * @package	TYPO3
 * @subpackage	tx_varnish
 */

class tx_varnish_hooks_tcemain {


	/**
	 * Clear cache hook
	 *
	 * @param array $params
	 * @param t3lib_tceMain $parent
	 */
	public function clearCachePostProc($params, &$parent) {
		$varnishController = t3lib_div::makeInstance('tx_varnish_controller');
		// use either cacheCmd or uid_page
		$cacheCmd = isset($params['cacheCmd']) ? $params['cacheCmd'] : $params['uid_page'];
		$varnishController->clearCache($cacheCmd);
	}

}

global $TYPO3_CONF_VARS;
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/varnish/classes/Hooks/class.tx_varnish_hooks_tcemain.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/varnish/classes/Hooks/class.tx_varnish_hooks_tcemain.php']);
}

?>
