<?php

namespace Opsone\Varnish\Middleware;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2020  Andri Steiner  <team@opsone.ch>
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

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Opsone\Varnish\Utility\VarnishGeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class FrontendSendHeader implements MiddlewareInterface
{

  public function process(
    ServerRequestInterface $request,
    RequestHandlerInterface $handler
  ): ResponseInterface {
    $response = $handler->handle($request);
    /** @var \TYPO3\CMS\Core\Http\NormalizedParams */
    $requestNormalizedParams = $request->getAttribute('normalizedParams');

    // determine whether we need to add the additional headers
    if(
      $requestNormalizedParams->isBehindReverseProxy() === true ||
      (int)VarnishGeneralUtility::getProperty('alwaysSendTypo3Headers') === 1
    ) {
      /** @var \GuzzleHttp\Psr7\ServerRequest $response */
      // add the TYPO3-Pid header
      $response = $response->withHeader(
        'TYPO3-Pid',
        (string)$request->getAttribute('routing')['pageId']
      );
      if ( false && $request->getMethod() == 'GET' ){
        /* TODO: Store 
         * $request->getAttribute('site')->getIdentifier()
         * $request->getAttribute('routing')['pageId']
         * language
         * $requestNormalizedParams->getHttpHost()? (protocol also? https is terminated before the varnish call )
         * $requestNormalizedParams->getRequestUri()
         * time()+$tsfe->get_cache_timeout() 
         * 
         * so the can be marked as invalid later 
         */
        
        //TODO:This way we can automatically crawl all URIs uris that have expired
        //TODO: but how to deal with pages that stop existing ? (renamed, deleted, etc)
        //TODO: we never reach here if we trigger a 404, so maybe the crawler should delete pages that dont response 200 OK ? (see other,not found, internal server error)
        //TODO: how do we crawl new pages ?
      }
      
      
      // add the TYPO3-Sitename header
      $response = $response->withHeader(
        'TYPO3-Sitename',
         VarnishGeneralUtility::getSitename()
      );

      if ( (int)VarnishGeneralUtility::getProperty('sendXkeyTags') === 1 ){
        /** @var \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController $tsfe */
        $tsfe=$GLOBALS['TSFE'];
        $tags=array_unique($tsfe->getPageCacheTags());
        //TODO add site config identifier  $request->getAttribute('site')->getIdentifier()        
        if ( empty($tags) ){
          //TODO: This can (aparently) happen if the first page request to a page that isn't cached by typo3 happened by a user with cookies ( fe_users, etc ) set
          //TODO: $tsfe->pageArguments->getArguments()['cHash']
          //TODO: store the tags by chash and fetch them later
          //TODO: This has yet to happen so far - mabe because we nuke all other cookies than be_user in varnish for everything besides pages that need it
          $response=$response->withHeader('X-no-tags','it happened');
        }
        if ( !empty($tags) ){
          $tags[]='siteId_'.VarnishGeneralUtility::getSitename();
          // add the xkey header
          $response = $response->withHeader(
            'xkey',
            implode(' ',$tags) //Not all xkey versions/implementations understand multiheader collapse - but they should all support single header collapsed with space
          );
        }
      }      
    }

    return $response;
  }

}

