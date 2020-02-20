<?php

namespace Opsone\Varnish\Controller;

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
use Opsone\Varnish\Utility\VarnishGeneralUtility;
use Opsone\Varnish\Utility\VarnishHttpUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

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
     * Load Configuration and assing default values
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

        // convert Comma separated List into a Array
        $this->instanceHostnames = GeneralUtility::trimExplode(',', $this->instanceHostnames, true);
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

        // if cacheCmd is a single Page, issue BAN Command on this pid
        // all other Commands ("page", "all") led to a BAN of the whole Cache
        $cacheCmd = (int)$cacheCmd;
        $command = array (
            $cacheCmd > 0 ? 'Varnish-Ban-TYPO3-Pid: ' . $cacheCmd : 'Varnish-Ban-All: 1',
            'Varnish-Ban-TYPO3-Sitename: ' . VarnishGeneralUtility::getSitename()
        );
        $method = VarnishGeneralUtility::getProperty('banRequestMethod') ?: 'BAN';

        // issue command on every Varnish Server
        /** @var $varnishHttp VarnishHttpUtility */
        $varnishHttp = GeneralUtility::makeInstance(VarnishHttpUtility::class);
        foreach ($this->instanceHostnames as $currentHost) {
            $varnishHttp::addCommand($method, $currentHost, $command);
        }
    }
}
