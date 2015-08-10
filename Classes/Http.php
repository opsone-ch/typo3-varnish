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
use Snowflake\Varnish\Utilities\GeneralUtility;

/**
 * This class communicates with the varnish server
 *
 * @author	Andri Steiner  <support@snowflake.ch>
 * @package	TYPO3
 * @subpackage	tx_varnish
 */
class Http {

	/**
	 * cURL Multi-Handle Queue
	 *
	 * @var Resource
	 */
	protected static $curlQueue;

	/**
	 * Class constructor
	 *
	 * @throws \Exception The Exception
	 * @return Http
	 */
	public function __construct() {
		// check whether the cURL PHP Extension is loaded
		if (!extension_loaded('curl')) {
			throw new \Exception('The cURL PHP Extension is required by ext_varnish.');
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
	 * @param	string	$method The methodname
	 * @param	string	$url	The url
	 * @param	string	$header	The header
	 *
	 * @return void
	 */
	public static function addCommand($method, $url, $header='') {
		// Header is expected as array always
		if (!is_array($header)) {
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
	 * Execute cURL Multi-Handle Queue
	 *
	 * @return	void
	 */
	protected static function runQueue() {
		GeneralUtility::devLog(__FUNCTION__);

		$running = NULL;
		do {
			curl_multi_exec(self::$curlQueue, $running);
		} while ($running);

		// destroy Handle which is not required anymore
		curl_multi_close(self::$curlQueue);
	}
}
