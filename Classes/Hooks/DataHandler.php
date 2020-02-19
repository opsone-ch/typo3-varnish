<?php

namespace Opsone\Varnish\Hooks;

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
use Opsone\Varnish\Controller\VarnishController;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * This class contains required hooks which are called by TYPO3
 *
 * @author    Andri Steiner  <team@opsone.ch>
 * @package    TYPO3
 * @subpackage    tx_varnish
 */
class DataHandler
{


    /**
     * Clear cache hook
     *
     * @param array $params Parameter
     * @param DataHandler $parent
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     *
     * @noinspection PhpUnusedParameterInspection $parent
     */
    public function clearCachePostProc(array $params, \TYPO3\CMS\Core\DataHandling\DataHandler $parent)
    {

        /** @var VarnishController $varnishController */
        $varnishController = GeneralUtility::makeInstance(VarnishController::class);
        // use either cacheCmd or uid_page
        $cacheCmd = isset($params['cacheCmd']) ? $params['cacheCmd'] : $params['uid_page'];
        $varnishController->clearCache($cacheCmd);
    }
}
