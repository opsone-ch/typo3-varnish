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

use TYPO3\CMS\Core\Cache\Exception\NoSuchCacheException;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Http\HtmlResponse;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Opsone\Varnish\Controller\VarnishController;

/**
 * This class contains required hooks which are called by TYPO3
 *
 * @author    Andri Steiner  <team@opsone.ch>
 * @package    TYPO3
 * @subpackage    tx_varnish
 */
class AjaxController
{
    /**
     * Ban all pages from varnish cache.
     *
     * @param ResponseInterface $response the current response
     * @return ResponseInterface
     * @throws NoSuchCacheException
     */
    public function banAll(): ResponseInterface
    {
        # log command
        if (is_object($GLOBALS['BE_USER'])) {
            $GLOBALS['BE_USER']->writelog(
                3,
                1,
                0,
                0,
                'User %s has cleared the Varnish cache',
                [$GLOBALS['BE_USER']->user['username']]
            );
        }

        /** @var VarnishController $varnishController */
        $varnishController = GeneralUtility::makeInstance(VarnishController::class);
        $varnishController->clearCache('all');
        return new HtmlResponse('');
    }
}
