<?php
namespace Snowflake\Varnish\Hooks;

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
	use TYPO3\CMS\Backend\Toolbar\ClearCacheActionsHookInterface;

/**
 * This class contains required hooks which are called by TYPO3
 *
 * @author	Andri Steiner  <support@snowflake.ch>
 * @package	TYPO3
 * @subpackage	tx_varnish
 */
class ClearCacheMenu implements ClearCacheActionsHookInterface {

	/**
	 * Add varnish cache clearing to clearcachemenu
	 *
	 * @param array $cacheActions The action
	 * @param array $optionValues The values
	 *
	 * @return void
	 */
	public function manipulateCacheActions(&$cacheActions, &$optionValues) {
		$title = $GLOBALS['LANG']->sL('LLL:EXT:varnish/Resources/Private/Language/locallang.xml:be_clear_cache_menu');
		$cacheActions[] = array(
			'id'    => 'varnish',
			'title' => $title,
			'href'  => 'ajax.php?ajaxID=tx_varnish::banAll',
			'icon'  => '<img src="/' . $GLOBALS['TYPO3_LOADED_EXT']['varnish']['siteRelPath'] . 'ext_icon.gif" title="' . $title . '" alt="' . $title . '" />',
		);
	}

}
