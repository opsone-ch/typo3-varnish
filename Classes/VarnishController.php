<?php
namespace Snowflake\Varnish;

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
	use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * This class contains controls communication between TYPO3 and Varnish
 *
 * @author	Andri Steiner  <support@snowflake.ch>
 * @package	TYPO3
 * @subpackage	tx_varnish
 */
class VarnishController {


	/**
	 * TYPO3 Extension Configuration
	 *
	 * @var Array
	 */
	public static $extConf;


	/**
	 * Load Configuration and assing default values
	 */
	public function __construct() {

		// load Extension Configuration
		self::$extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['varnish']);

		// assign default values
		if (empty(self::$extConf['instanceHostnames'])) {
			self::$extConf['instanceHostnames'] = GeneralUtility::getIndpEnv('HTTP_HOST');
		}

		// convert Comma separated List into a Array
		self::$extConf['instanceHostnames'] = GeneralUtility::trimExplode(',', self::$extConf['instanceHostnames']);

	}


	/**
	 * ClearCache
	 * Executed by the clearCachePostProc Hook
	 *
	 * @param string $cacheCmd Cache Command, see Description in t3lib_tcemain
	 *
	 * @return	void
	 */
	public function clearCache($cacheCmd) {

		\Snowflake\Varnish\Utilities\GeneralUtility::devLog('clearCache', array('cacheCmd' => $cacheCmd));

		// if cacheCmd is a single Page, issue BAN Command on this pid
		// all other Commands ("page", "all") led to a BAN of the whole Cache
		$cacheCmd = intval($cacheCmd);
		$command = array(
			$cacheCmd > 0 ? 'Varnish-Ban-TYPO3-Pid: ' . $cacheCmd : 'Varnish-Ban-All: 1',
			'Varnish-Ban-TYPO3-Sitename: ' . \Snowflake\Varnish\Utilities\GeneralUtility::getSitename()
		);

		$method = self::$extConf['banRequestMethod'] ? self::$extConf['banRequestMethod'] : 'BAN';

		// issue command on every Varnish Server
		/** @var $varnishHttp \Snowflake\Varnish\Http */
		$varnishHttp = GeneralUtility::makeInstance('\\Snowflake\\Varnish\\Http');
		foreach (self::$extConf['instanceHostnames'] as $currentHost) {
			$varnishHttp::addCommand($method, $currentHost, $command);
		}
	}
}
