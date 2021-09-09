<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2017  Andri Steiner  <team@opsone.ch>
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

defined('TYPO3') or die();

// Frontend: Load TyposSript to enable required FE settings by default
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript(
	'varnish',
	'setup',
	"@import 'EXT:varnish/Configuration/TypoScript/setup.typoscript'");

// Icon Registry
$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
	\TYPO3\CMS\Core\Imaging\IconRegistry::class
);
$iconRegistry->registerIcon(
	'tx-varnish-logo',
	\TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
	['source' => 'EXT:varnish/ext_icon.gif']
);

// Backend Hooks
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['additionalBackendItems']['cacheActions'][] = Opsone\Varnish\Hooks\ClearCacheMenu::class;
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearCachePostProc'][] = 'Opsone\\Varnish\\Hooks\\DataHandler->clearCachePostProc';

