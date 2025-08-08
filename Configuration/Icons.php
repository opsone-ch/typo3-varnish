<?php

use TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider;

return [
    'tx-varnish-logo' => [
        'provider' => BitmapIconProvider::class,
        'source' => 'EXT:varnish/Resources/Public/Icons/Extension.gif',
    ],
];
