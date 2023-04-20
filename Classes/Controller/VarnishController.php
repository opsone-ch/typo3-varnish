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

namespace Opsone\Varnish\Controller;

use Opsone\Varnish\Utility\VarnishGeneralUtility;
use Opsone\Varnish\Utility\VarnishHttpUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;

/**
 * This class contains controls communication between TYPO3 and Varnish
 *
 * @author    Andri Steiner  <team@opsone.ch>
 * @package    TYPO3
 * @subpackage    tx_varnish
 */
class VarnishController
{
    /**
     * List of Varnish hostnames
     *
     * @var array
     */
    protected $instanceHostnames = array ();

    /**
     * List of extra headers to send to the Varnish host
     *
     * @var array
     */
    protected $extraHeaders = array ();

    /**
     * Internal backend Instance
     *
     * @var string
     */
    protected $internalServer = '';


    /**
     * Load Configuration and assign default values
     *
     * @throws \UnexpectedValueException
     */
    public function __construct()
    {
        // assign Varnish daemon hostnames
        $this->instanceHostnames = VarnishGeneralUtility::getProperty('instanceHostnames');
        if (empty($this->instanceHostnames)) {
            $this->instanceHostnames = GeneralUtility::getIndpEnv('HTTP_HOST');
        }
        // convert comma separated list into an array
        $this->instanceHostnames = GeneralUtility::trimExplode(',', $this->instanceHostnames, true);

        // assign intern server name
        $this->internalServer = VarnishGeneralUtility::getProperty('administrativeHost');

        // assign extra headers
        $this->extraHeaders = VarnishGeneralUtility::getProperty('extraAdministrativeHeaders');
        if (!empty($this->extraHeaders)) {
            $this->extraHeaders = GeneralUtility::trimExplode('|', $this->extraHeaders, true);
        }
    }

    /**
     * ClearCache
     * Executed by the clearCachePostProc Hook
     *
     * @param string|int $cacheCmd Cache Command, see Description in t3lib_tcemain
     *
     * @return    void
     *
     * @throws \InvalidArgumentException
     */
    public function clearCache($cacheCmd)
    {
        // if cacheCmd is -1, were in a draft workspace and skip Varnish clearing all together
        if ($cacheCmd === -1) {
            return;
        }

        // Log debug infos
        VarnishGeneralUtility::devLog('clearCache', array ('cacheCmd' => $cacheCmd));

        $headers = ['Varnish-Ban-TYPO3-Sitename: ' . VarnishGeneralUtility::getSitename()];

        switch ($cacheCmd) {
            case 'all':
            case 'pages':
                $headers[] = 'Varnish-Ban-All: 1';
                break;
            default:
                if (MathUtility::canBeInterpretedAsInteger($cacheCmd)) {
                    $headers[] = 'Varnish-Ban-TYPO3-Pid: ' . $cacheCmd;
                } else {
                    $headers[] = 'xkey-purge: ' . $cacheCmd;
                }
        }
        $method = VarnishGeneralUtility::getProperty('banRequestMethod') ?: 'BAN';

        if (empty($this->extraHeaders)) {
            $headers = array_merge($headers, $this->extraHeaders);
        }

        // issue command on every Varnish Server
        /** @var $varnishHttp VarnishHttpUtility */
        $varnishHttp = GeneralUtility::makeInstance(VarnishHttpUtility::class);
        foreach ($this->instanceHostnames as $currentHost) {
            if (!empty($this->internalServer)) {
                VarnishGeneralUtility::devLog('clearCache', array_merge($headers, ['Host: ' . $currentHost]));
                $varnishHttp::addCommand($method, $this->internalServer, array_merge($headers, ['Host: ' . $currentHost]));
            } else {
                VarnishGeneralUtility::devLog('clearCache', array('headers' => $headers));
                $varnishHttp::addCommand($method, $currentHost, $headers);
            }
        }
    }
}
