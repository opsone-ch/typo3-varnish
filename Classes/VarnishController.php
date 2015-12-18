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
	 * List of Varnish hostnames
	 * @var Array
	 */
	protected static $instanceHostnames = array();


	/**
	 * Load Configuration and assing default values
	 */
	public function __construct() {
		// assign Varnish daemon hostnames
		$this->instanceHostnames = \Snowflake\Varnish\Utilities\GeneralUtility::getProperty('instanceHostnames');
		if (empty($this->instanceHostnames)) {
			$this->instanceHostnames = GeneralUtility::getIndpEnv('HTTP_HOST');
		}

		// convert Comma separated List into a Array
		$this->instanceHostnames = GeneralUtility::trimExplode(',', $this->instanceHostnames, TRUE);
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
		$method = \Snowflake\Varnish\Utilities\GeneralUtility::getProperty('banRequestMethod') ? \Snowflake\Varnish\Utilities\GeneralUtility::getProperty('banRequestMethod') : 'BAN';

		// issue command on every Varnish Server
		/** @var $varnishHttp \Snowflake\Varnish\Http */
		$varnishHttp = GeneralUtility::makeInstance('Snowflake\\Varnish\\Http');
		foreach ($this->instanceHostnames as $currentHost) {
			$varnishHttp::addCommand($method, $currentHost, $command);
		}
	}
}
