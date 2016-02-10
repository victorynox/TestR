<?php

return [
    'dependencies' => [
        'invokables' => [
            Zend\Expressive\Router\RouterInterface::class => Zend\Expressive\Router\FastRouteRouter::class,
        ],
        // Map middleware -> factories here
        'factories' => [
         //   App\Action\HelloAction::class => App\Action\HelloActionFactory::class,
        ],
    ],

    'routes' => [
        // Example:
        // [
        //     'name' => 'home',
        //     'path' => '/',
        //     'middleware' => App\Action\HomePageAction::class,
        //     'allowed_methods' => ['GET'],
        // ],
        [
            'name' => 'hello',
            'path' => '/',
            'middleware' => App\Action\HelloAction::class,
            'allowed_method' => ['GET'],
        ]
    ],
];
