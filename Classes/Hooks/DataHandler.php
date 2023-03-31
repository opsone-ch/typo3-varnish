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

namespace Opsone\Varnish\Hooks;

use Opsone\Varnish\Controller\VarnishController;
use Opsone\Varnish\Utility\VarnishGeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
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
     * @param \TYPO3\CMS\Core\DataHandling\DataHandler $parent
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
        if (isset($params['cacheCmd'])) {
            $cacheCmd = $params['cacheCmd'];
        } else {
            if (VarnishGeneralUtility::getProperty('sendXkeyTags')) {
                $cacheCmd = [];
                //Tags that goes too far, eg "tt_content" is sendt every time you update ANY content element!
                $badTags = ['tt_content','sys_template','sys_file_reference'];
                foreach ($params['tags'] as $key => $val) {
                    if ($val !== true || in_array($key, $badTags)) {
                        continue;
                    }

                    //Use valid tags for xkey banning
                    $cacheCmd[] = $key;

                    //Handles clearing pages where content is linked
                    if (strpos($key, 'tt_content_') === 0) {
                        /**
                         * @var \TYPO3\CMS\Core\Database\Query\QueryBuilder
                         */
                        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
                            ->getQueryBuilderForTable('tt_content');
                        $query = $queryBuilder
                            ->selectLiteral('DISTINCT pid')
                            ->from('tt_content')
                            ->where(
                                $queryBuilder->expr()
                                    ->like(
                                        'records',
                                        $queryBuilder->createNamedParameter(
                                            '%' . $queryBuilder->escapeLikeWildcards($key) . '%',
                                            \TYPO3\CMS\Core\Database\Connection::PARAM_STR
                                        )
                                    )
                            );
                        if (method_exists($query, 'executeQuery')) {
                            //t3 v10
                            $linkingPids = $query
                                ->execute()
                                ->fetchAll();
                        } else {
                            //t3 v11+
                            $linkingPids = $query
                                ->executeQuery()
                                ->fetchAllAssociative();
                        }
                        foreach ($linkingPids as $row) {
                            $parent->clear_cacheCmd($row['pid']);
                        }
                    }
                }
                $cacheCmd = implode(' ', $cacheCmd);
            } else {
                $cacheCmd = $params['uid_page'];
            }
            VarnishGeneralUtility::devLog('clearCachePostProc', $params);
        }
        $varnishController->clearCache($cacheCmd);
    }
}
