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

class FrontendSendHeader implements MiddlewareInterface
{

  public function process(
    ServerRequestInterface $request,
    RequestHandlerInterface $handler
  ): ResponseInterface {
    $response = $handler->handle($request);
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

      // add the TYPO3-Sitename header
      $response = $response->withHeader(
        'TYPO3-Sitename',
         VarnishGeneralUtility::getSitename()
      );

      if ( (int)VarnishGeneralUtility::getProperty('sendXkeyTags') === 1 ){
        /** @var \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController $tsfe */
        $tsfe=$GLOBALS['TSFE'];
        $tags=array_unique($tsfe->getPageCacheTags());
        
        if ( empty($tags) ){
          //This can happen if the first page request to a page that isn't cached by typo3 happened by a user with cookies ( fe_users, etc ) set
          //$tsfe->pageArguments->getArguments()['cHash']
        }
        if ( !empty($tags) ){
          $tags[]='siteID_'.VarnishGeneralUtility::getSitename();
          // add the xkey header
          $response = $response->withHeader(
            'xkey',
            $tags
          );
        }
      }      
    }

    return $response;
  }

}

