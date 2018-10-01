<?php
namespace Snowflake\Varnish\Utility;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 snowflake Ops AG <team@snowflakeops.ch>
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
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Helper class for varnish
 *
 * @author    Sascha Hepp <team@snowflakeops.ch>
 * @package    TYPO3
 * @subpackage    tx_varnish
 */
class VarnishGeneralUtility
{


    public static $extConf;


    /**
     * Devlog if enabled
     *
     * @param string $functionName Functionname
     * @param string $additionalData The log data
     *
     * @return void
     */
    public static function devLog($functionName, $additionalData = [])
    {
        self::loadExtConf();
        if (self::$extConf['enableDevLog']) {
            $logger = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Log\LogManager::class)->getLogger(__CLASS__);
            $logger->warning('varnish: '.$functionName, $additionalData);
        }
    }


    /**
     * Load extension configuration
     *
     * @return void
     */
    protected static function loadExtConf()
    {

        // load Extension Configuration
        if (version_compare(TYPO3_version, '9', '>=')) {
            if (empty(self::$extConf)) {
                self::$extConf = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class)->get('varnish');
            }
        } else if (version_compare(TYPO3_version, '8', '>=')) {
            self::$extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['varnish']);

            if (!empty($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['varnish'])) {
                self::$extConf = array_merge(self::$extConf, $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['varnish']);
            }
        }

    }


    /**
     * Returns HMAC of the sitename
     *
     * @return mixed
     */
    public static function getSitename()
    {
        return GeneralUtility::hmac($GLOBALS['TYPO3_CONF_VARS']['SYS']['sitename']);
    }


    /**
     * getProperty from extension configuration
     *
     * @param string $key property name
     *
     * @return array
     */
    public static function getProperty($key)
    {
        self::loadExtConf();

        $value = null;
        if (isset(self::$extConf[$key])) {
            $value = self::$extConf[$key];
        }

        return $value;
    }
}
