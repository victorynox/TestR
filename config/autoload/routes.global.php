<?php

return [
    'dependencies' => [
        'invokables' => [
            Zend\Expressive\Router\RouterInterface::class => Zend\Expressive\Router\FastRouteRouter::class,
        ],
        // Map middleware -> factories here
        'factories' => [
            App\Action\HelloAction::class => App\Action\Factory\HelloActionFactory::class,
            App\Middleware\RScriptValidatorMiddleware::class =>  App\Middleware\Factory\RScriptValidatorFactory::class,
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
        ],
        [
            'name' => 'api',
            'path' => '/api/{script_name:[a-zA-Z]{1,40}}',
            'middleware' => [
                App\Middleware\RScriptValidatorMiddleware::class,
                App\Middleware\RScriptMiddleware::class,
                //App\Middleware\
            ],
            'allowed_method' => ['GET'],
        ],
        /*[
            'name' => 'api',
            'path' => '/api/{script_name:[a-zA-Z]{1,40}}',
            'middleware' => [
                App\Middleware\RScriptMiddleware::class,
            ],
            'allowed_method' => ['GET'],
        ]*/
    ],
];
