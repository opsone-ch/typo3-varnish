<?php

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

namespace Opsone\Varnish\Middleware;

use TYPO3\CMS\Core\Http\NormalizedParams;
use GuzzleHttp\Psr7\ServerRequest;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use Opsone\Varnish\Events\ProcessXtagsEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Opsone\Varnish\Utility\VarnishGeneralUtility;

class FrontendSendHeader implements MiddlewareInterface
{
    public function __construct(private readonly ?EventDispatcherInterface $eventDispatcher)
    {
    }
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $response = $handler->handle($request);
        /** @var NormalizedParams */
        $requestNormalizedParams = $request->getAttribute('normalizedParams');

        // determine whether we need to add the additional headers
        if (
            $requestNormalizedParams->isBehindReverseProxy() === true ||
            (int)VarnishGeneralUtility::getProperty('alwaysSendTypo3Headers') === 1
        ) {
            /** @var ServerRequest $response */
            if (!$response->hasHeader('TYPO3-Pid')) {
                // add the TYPO3-Pid header
                $response = $response->withHeader(
                    'TYPO3-Pid',
                    (string)$request->getAttribute('routing')['pageId']
                );
            }

            // add the TYPO3-Sitename header
            $response = $response->withHeader(
                'TYPO3-Sitename',
                VarnishGeneralUtility::getSitename()
            );

            if ((int)VarnishGeneralUtility::getProperty('sendXkeyTags') === 1) {
                /** @var TypoScriptFrontendController $tsfe */
                $tsfe = $GLOBALS['TSFE'];
                $tags = array_unique(
                    $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.cache.collector')->getCacheTags()
                );
                //PSR-14 signal to process $tags before we send them
                /** @var ProcessXtagsEvent */
                $event = $this->eventDispatcher->dispatch(new ProcessXtagsEvent($tags));
                $tags = $event->getXtags();

                if (empty($tags)) {
                    /*
                     * This can (aparently) happen if the first page request to a page that isn't cached by typo3
                     * happened by a user with cookies ( fe_users, etc ) set
                     * $tsfe->pageArguments->getArguments()['cHash']
                     * store the tags by chash and fetch them later
                     * This has yet to happen so far - mabe because we nuke all other cookies than be_user in varnish
                     * for everything besides pages that need it
                     */
                    $response = $response->withHeader('X-no-tags', 'it happened');
                }
                if (!empty($tags)) {
                    $tags[] = 'siteId_' . VarnishGeneralUtility::getSitename();
                    // add the xkey header
                    $response = $response->withHeader(
                        'xkey',
                        // not all xkey versions/implementations understand multiheader collapse - but they should all
                        // support single header collapsed with space
                        implode(' ', $tags)
                    );
                }
            }
        }

        return $response;
    }
}
