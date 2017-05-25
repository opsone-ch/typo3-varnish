<?php

namespace Snowflake\Varnish\Hooks;

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

use TYPO3\CMS\Backend\Toolbar\ClearCacheActionsHookInterface;
use TYPO3\CMS\Backend\Utility\BackendUtility;


/**
 * This class contains required hooks which are called by TYPO3
 *
 * @author    Andri Steiner  <team@snowflakeops.ch>
 * @package    TYPO3
 * @subpackage    tx_varnish
 */
class ClearCacheMenu implements ClearCacheActionsHookInterface
{


    /**
     * Add varnish cache clearing to clearcachemenu
     *
     * @param array $cacheActions The action
     * @param array $optionValues The values
     *
     * @return void
     */
    public function manipulateCacheActions(&$cacheActions, &$optionValues)
    {
        // show menu button only admins
        if (!$GLOBALS['BE_USER']->isAdmin()) {
            return;
        }

        if (version_compare(TYPO3_version, '8.7', '>=')) {
            $cacheActions[] = array (
                'id' => 'varnish',
                'title' => 'LLL:EXT:varnish/Resources/Private/Language/locallang.xml:be_clear_cache_title',
                'description' => 'LLL:EXT:varnish/Resources/Private/Language/locallang.xml:be_clear_cache_description',
                'href' => BackendUtility::getAjaxUrl('varnish_banall'),
                'iconIdentifier' => 'tx-varnish-logo',
            );
        } else if (version_compare(TYPO3_version, '7.6', '>=')) {
            $cacheActions[] = array (
                'id' => 'varnish',
                'title' => $GLOBALS['LANG']->sL('LLL:EXT:varnish/Resources/Private/Language/locallang.xml:be_clear_cache_title'),
                'description' => $GLOBALS['LANG']->sL('LLL:EXT:varnish/Resources/Private/Language/locallang.xml:be_clear_cache_description'),
                'href' => BackendUtility::getAjaxUrl('varnish_banall'),
                'icon' => '<img src="/typo3conf/ext/varnish/ext_icon.gif" title="Varnish Ban All" alt="Varnish Extension Icon" />',
            );
        }

    }

}
