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
 * This class communicates with the varnish server
 *
 * @author	Andri Steiner  <support@snowflake.ch>
 * @package	TYPO3
 * @subpackage	tx_varnish
 */

class tx_varnish_http {


	/**
	 * cURL Multi-Handle Queue
	 *
	 * @var Resource
	 */
	protected static $curlQueue;



	/**
	 * Class constructor
	 *
	 *
	 * @throws Exception
	 * @return \tx_varnish_http
	 */

	public function __construct() {

		// check whether the cURL PHP Extension is loaded
		if (!extension_loaded('curl')) {
			throw new Exception('The cURL PHP Extension is required by ext_varnish.');
		}

		// initialize cURL Multi-Handle Queue
		self::initQueue();

	}


	/**
	 * Class destructor
	 *
	 * @return	void
	 */

	public function __destruct() {

		// execute cURL Multi-Handle Queue
		self::runQueue();

	}


	/**
	 * Add command to cURL Multi-Handle Queue
	 *
	 * @param	String	$method
	 * @param	String	$url
	 * @param	String	$header
	 */

	public static function addCommand($method, $url, $header='') {

		// Header is expected as array always
		if(!is_array($header)) {
			$header = array($header);
		}

		// create Handle and at it to the Multi-Handle Queue
		$curlHandle = curl_init();
		$curlOptions = array(
			CURLOPT_CUSTOMREQUEST	=> $method,
			CURLOPT_URL				=> $url,
			CURLOPT_HTTPHEADER		=> $header,
			CURLOPT_TIMEOUT			=> 1,
			CURLOPT_RETURNTRANSFER  => 1,
		);

		curl_setopt_array($curlHandle, $curlOptions);
		curl_multi_add_handle(self::$curlQueue, $curlHandle);

	}


	/**
	 * Initialize cURL Multi-Handle Queue
	 *
	 * @return	void
	 */

	protected static function initQueue() {

		self::$curlQueue = curl_multi_init();

	}


	/**
	 * Execute cURL Mutli-Handle Queue
	 *
	 * @return	void
	 */

	protected static function runQueue() {

		tx_varnish_generalutility::devLog(__FUNCTION__);

		$running = null;
		do {
			curl_multi_exec(self::$curlQueue, $running);
		} while($running);

		// destroy Handle which is not required anymore
		curl_multi_close(self::$curlQueue);

	}


}

global $TYPO3_CONF_VARS;
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/varnish/classes/class.tx_varnish_http.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/varnish/classes/class.tx_varnish_http.php']);
}

?>