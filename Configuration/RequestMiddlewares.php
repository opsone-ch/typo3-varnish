<?php

use Opsone\Varnish\Middleware\FrontendSendHeader;

return [
    'frontend' => [
        'opsone/varnish/frontend/send-header' => [
            'target' => FrontendSendHeader::class,
            'after' => [
                'typo3/cms-frontend/page-resolver',
            ],
        ],
    ],
];
