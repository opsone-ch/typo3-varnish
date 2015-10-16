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
	use TYPO3\CMS\Core\Utility\GeneralUtility;
	use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * This class contains required hooks which are called by TYPO3
 *
 * @author	Andri Steiner  <support@snowflake.ch>
 * @package	TYPO3
 * @subpackage	tx_varnish
 */
class Frontend {

	/**
	 * ContentPostProc-output hook to add typo3-pid header
	 *
	 * @param array $parameters Parameter
	 * @param \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController $parent The parent object
	 *
	 * @return void
	 */
	public function sendHeader(array $parameters, TypoScriptFrontendController $parent) {
		// Send Page pid which is used to issue BAN Command against
		if (GeneralUtility::getIndpEnv('TYPO3_REV_PROXY') == 1 || \Snowflake\Varnish\Utilities\GeneralUtility::getProperty('alwaysSendTypo3Headers') == 1) {
			header('TYPO3-Pid: ' . $parent->id);
			header('TYPO3-Sitename: ' . \Snowflake\Varnish\Utilities\GeneralUtility::getSitename());
		}
	}

}
