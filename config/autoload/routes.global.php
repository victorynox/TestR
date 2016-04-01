<?php

return [
    'dependencies' => [
        'invokables' => [
            Zend\Expressive\Router\RouterInterface::class => Zend\Expressive\Router\FastRouteRouter::class
        ],
        // Map middleware -> factories here
        'factories' => [
            App\Action\HelloAction::class => App\Action\HelloActionFactory::class,
            Auth\AuthenticationMiddleware::class => Auth\AuthenticationFactory::class,
            Auth\Action\LogoutAction::class =>   Auth\Action\LogoutActionFactory::class,
            zaboy\rest\Pipe\RestPipe::class => App\DataStore\Pipes\Factory\RestPipeFactory::class,
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
            'allowed_method' => ['GET', 'POST'],
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
            'name' => 'restAPI',
            'path' => '/rest[/{resourceName}[/{id}]]',
            'middleware' =>  zaboy\rest\Pipe\RestPipe::class,
            'allowed_method' => ['GET', 'POST'],
        ]

    ],
];
