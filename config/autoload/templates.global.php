<?php

return [
    'dependencies' => [
        'factories' => [
            'Zend\Expressive\FinalHandler' =>
                Zend\Expressive\Container\TemplatedErrorHandlerFactory::class,

            Zend\Expressive\Template\TemplateRendererInterface::class =>
                Zend\Expressive\Twig\TwigRendererFactory::class,
        ],
    ],

    'templates' => [
        'extension' => 'html.twig',
        'paths'     => [
            'app'    => ['templates/app'],
            'auth'    => ['templates/auth'],
            'ebay'    => ['templates/ebay'],
            'rreport'    => ['templates/rreport'],
            //'layout' => ['templates/layout'],
            'error'  => ['templates/error'],
            'macros' => ['templates/macros'],
            'layout' => ['templates/layout'],
        ],
    ],

    'twig' => [
        'cache_dir'      => 'data/cache/twig',
        'assets_url'     => '/',
        'assets_version' => null,
        'extensions'     => [
            // extension service names or instances
        ],
    ],
];
