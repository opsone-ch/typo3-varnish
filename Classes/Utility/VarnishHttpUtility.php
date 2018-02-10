<?php

namespace Snowflake\Varnish\Utility;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012  Andri Steiner  <team@snowflakeops.ch>
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
 * @author    Andri Steiner  <team@snowflakeops.ch>
 * @package    TYPO3
 * @subpackage    tx_varnish
 */
class VarnishHttpUtility
{


    /**
     * cURL Multi-Handle Queue
     *
     * @var Resource
     */
    protected static $curlQueue;

    /*
     * Array of individual curl handles
     *
     * @var Array
     */
    protected static $curlHandles;

    /**
     * Class constructor
     *
     * @throws \RuntimeException The Exception
     */
    public function __construct()
    {
        // check whether the cURL PHP Extension is loaded
        if (!extension_loaded('curl')) {
            throw new \RuntimeException('The cURL PHP Extension is required by ext_varnish.');
        }

        // initialize cURL Multi-Handle Queue
        self::initQueue();
    }


    /**
     * Initialize cURL Multi-Handle Queue
     *
     * @return    void
     */
    protected static function initQueue()
    {
        self::$curlQueue = curl_multi_init();
    }


    /**
     * Add command to cURL Multi-Handle Queue
     *
     * @param    string $method The methodname
     * @param    string $url The url
     * @param    string|array $header The header
     *
     * @return void
     */
    public static function addCommand($method, $url, $header = '')
    {
        // Header is expected as array always
        /** @noinspection ArrayCastingEquivalentInspection */
        if (!is_array($header)) {
            $header = array ($header);
        }

        // create Handle and at it to the Multi-Handle Queue
        $curlHandle = curl_init();
        self::$curlHandles[] = $curlHandle;
        $curlOptions = array (
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_TIMEOUT => 1,
            CURLOPT_RETURNTRANSFER => 1,
            # If your varnish is behind proxy working with 302 redirects cUrl should follow it
            CURLOPT_FOLLOWLOCATION => 1,
            # Especially in development you might have only a self signed SSL certificate
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
        );

        curl_setopt_array($curlHandle, $curlOptions);
        curl_multi_add_handle(self::$curlQueue, $curlHandle);
    }


    /**
     * Class destructor
     *
     * @return    void
     */
    public function __destruct()
    {
        // execute cURL Multi-Handle Queue
        self::runQueue();
    }


    /**
     * Execute cURL Multi-Handle Queue
     *
     * @return    void
     */
    protected static function runQueue()
    {
        VarnishGeneralUtility::devLog(__FUNCTION__);
        $running = null;
        do {
            curl_multi_exec(self::$curlQueue, $running);

            $info = curl_multi_info_read(self::$curlQueue);
            // cUrl request had errors
            if ($info !== false) {
                VarnishGeneralUtility::devLog(__FUNCTION__ . ': curl_multi_info_read', $info);
                VarnishGeneralUtility::devLog(__FUNCTION__ . ': curl_multi_info_read', array('msg' => curl_error($info['handle'])));
            }
        } while ($running);

        // destroy Handle which is not required anymore
        curl_multi_close(self::$curlQueue);
    }
}
