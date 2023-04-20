<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012  Andri Steiner  <team@opsone.ch>
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

namespace Opsone\Varnish\Utility;

/**
 * This class communicates with the varnish server
 *
 * @author    Andri Steiner  <team@opsone.ch>
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


    /**
     * Class constructor
     *
     * @throws \RuntimeException The Exception
     */
    public function __construct()
    {
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

        // create Handle and add it to the Multi-Handle Queue
        $curlHandle = curl_init();
        $curlOptions = array (
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_TIMEOUT => 1,
            CURLOPT_RETURNTRANSFER => 1,
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
        } while ($running);

        // destroy Handle which is not required anymore
        curl_multi_close(self::$curlQueue);
    }
}
