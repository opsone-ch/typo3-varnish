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

if(!defined('TYPO3_MODE')) die ('Access denied.');

$extPath = t3lib_extMgm::extPath('varnish');
return array(
	'tx_varnish_controller'			=> $extPath . 'classes/class.tx_varnish_controller.php',
	'tx_varnish_http'			=> $extPath . 'classes/class.tx_varnish_http.php',

	// hooks
	'tx_varnish_hooks_ajax'			=> $extPath . 'classes/Hooks/class.tx_varnish_hooks_ajax.php',
	'tx_varnish_hooks_clearcachemenu'	=> $extPath . 'classes/Hooks/class.tx_varnish_hooks_clearcachemenu.php',
	'tx_varnish_hooks_tcemain'		=> $extPath . 'classes/Hooks/class.tx_varnish_hooks_tcemain.php',
	'tx_varnish_hooks_tslib_fe'		=> $extPath . 'classes/Hooks/class.tx_varnish_hooks_tslib_fe.php',

	'tx_varnish_generalutility'		=> $extPath . 'classes/Utilities/class.tx_varnish_generalutility.php',
);

?>
