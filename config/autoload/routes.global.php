<?php

return [
    'dependencies' => [
        'invokables' => [
            Zend\Expressive\Router\RouterInterface::class => Zend\Expressive\Router\FastRouteRouter::class,
            victorynox\Ebay\Finding\Action\FindItemsIneBayStoreAction::class => victorynox\Ebay\Finding\Action\FindItemsIneBayStoreAction::class
        ],
        // Map middleware -> factories here
        'factories' => [
            victorynox\RReports\Action\HelloAction::class => victorynox\RReports\Action\HelloActionFactory::class,
            victorynox\Auth\Middleware\AuthenticationMiddleware::class => victorynox\Auth\Middleware\AuthenticationFactory::class,
            victorynox\Auth\Action\LogoutAction::class =>   victorynox\Auth\Action\LogoutActionFactory::class,
            zaboy\rest\Pipe\RestRql::class => victorynox\DataStore\Pipes\Factory\RestPipeFactory::class,
            victorynox\Ebay\Notification\Action\NotificationViewAction::class => \victorynox\Ebay\Notification\Action\NotificationViewFactory::class,
            victorynox\Ebay\Notification\Action\NotificationAction::class => \victorynox\Ebay\Notification\Action\NotificationFactory::class,
            victorynox\Ebay\Notification\Action\GetNotificationDataAction::class => \victorynox\Ebay\Notification\Action\GetNotificationDataFactory::class,
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
            'name' => 'rreport',
            'path' => '/',
            'middleware' => victorynox\RReports\Action\HelloAction::class,
            'allowed_method' => ['GET', 'POST'],
        ],

        [
            'name' => 'auth',
            'path' => '/auth',
            'middleware' => victorynox\Auth\Middleware\AuthenticationMiddleware::class,
            'allowed_method' => ['GET', 'POST'],
        ],

        [
            'name' => 'logout',
            'path' => '/auth/logout',
            'middleware' => victorynox\Auth\Action\LogoutAction::class,
            'allowed_method' => ['GET'],
        ],

        [
            'name' => 'restAPI',
            'path' => '/rest[/{resourceName}[/{id}]]',
            'middleware' =>  zaboy\rest\Pipe\RestRql::class,
            'allowed_method' => ['GET', 'POST', 'DELETE', 'PUSH'],
        ],

        [
            'name' => 'notification',
            'path' => '/ebay/notification',
            'middleware' =>  victorynox\Ebay\Notification\Action\NotificationAction::class,
            'allowed_method' => ['GET'],
        ],
        [
            'name' => 'notification.view',
            'path' => '/ebay/notifications/view',
            'middleware' =>  victorynox\Ebay\Notification\Action\NotificationViewAction::class,
            'allowed_method' => ['GET'],
        ],
        [
            'name' => 'ge.notification.data',
            'path' => '/ebay/notifications/data/{id}',
            'middleware' =>  victorynox\Ebay\Notification\Action\GetNotificationDataAction::class,
            'allowed_method' => ['GET'],
        ],
        [
            'name' => 'findItemByStore',
            'path' => '/ebay/find-item',
            'middleware' =>  victorynox\Ebay\Finding\Action\FindItemsIneBayStoreAction::class,
            'allowed_method' => ['GET'],
        ]


    ],
];
