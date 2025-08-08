<?php

declare(strict_types=1);

namespace Opsone\Varnish\EventListener;

use Opsone\Varnish\Controller\VarnishController;
use TYPO3\CMS\Core\Cache\Event\CacheFlushEvent;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class CacheFlushEventListener
{
    public function __invoke(CacheFlushEvent $cacheFlushEvent): void
    {
        $varnishController = GeneralUtility::makeInstance(VarnishController::class);
        $varnishController->clearCache('all');
    }
}
