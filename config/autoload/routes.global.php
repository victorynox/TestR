<?php

return [
    'dependencies' => [
        'invokables' => [
            Zend\Expressive\Router\RouterInterface::class => Zend\Expressive\Router\FastRouteRouter::class,
            Auth\Action\LogoutAction::class =>   Auth\Action\LogoutAction::class,
        ],
        // Map middleware -> factories here
        'factories' => [
            App\Action\HelloAction::class => App\Action\HelloActionFactory::class,
            App\Middleware\RScriptValidatorMiddleware::class =>  App\Middleware\RScriptValidatorFactory::class,
            Auth\AuthenticationMiddleware::class => Auth\AuthenticationFactory::class
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
            'name' => 'auth',
            'path' => '/auth',
            'middleware' => Auth\AuthenticationMiddleware::class,
            'allowed_method' => ['GET', 'POST'],
        ],
        [
            'name' => 'logout',
            'path' => '/auth/logout',
            'middleware' => Auth\Action\LogoutAction::class,
            'allowed_method' => ['GET'],
        ],
        [
            'name' => 'api',
            'path' => '/api/{script_name:[a-zA-Z]{1,40}}',
            'middleware' => [
                App\Middleware\RScriptValidatorMiddleware::class,
                App\Action\RScriptMiddleware::class,
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
