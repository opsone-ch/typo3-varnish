<?php

return [
    'frontend' => [
        'opsone/varnish/frontend/send-header' => [
            'target' => \Opsone\Varnish\Middleware\FrontendSendHeader::class,
        ],
    ],
];
