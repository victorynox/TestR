<?php

return [
    'dependencies' => [
        'invokables' => [
            Zend\Expressive\Router\RouterInterface::class => Zend\Expressive\Router\FastRouteRouter::class,
            victorynox\Ebay\Finding\Action\FindItemsIneBayStoreAction::class => victorynox\Ebay\Finding\Action\FindItemsIneBayStoreAction::class
        ],
        // Map middleware -> factories here
        'factories' => [
            victorynox\AnalyticReports\Action\HelloAction::class => victorynox\AnalyticReports\Action\HelloActionFactory::class,
            victorynox\Auth\Middleware\AuthenticationMiddleware::class => victorynox\Auth\Middleware\AuthenticationFactory::class,
            victorynox\Auth\Action\LogoutAction::class =>   victorynox\Auth\Action\LogoutActionFactory::class,
            zaboy\rest\Pipe\RestRql::class => victorynox\DataStore\Pipes\Factory\RestPipeFactory::class,
            victorynox\Ebay\Category\Action\CategoryTreeAction::class => victorynox\Ebay\Category\Action\CategoryTreeFactory::class,
            victorynox\Notification\Action\NotificationViewAction::class => \victorynox\Notification\Action\NotificationViewFactory::class,
            victorynox\Notification\Action\NotificationAction::class => \victorynox\Notification\Action\NotificationFactory::class,
            victorynox\Ebay\Trading\Action\GetItemTransactionsAction::class => victorynox\Ebay\Trading\Action\GetItemTransactionsFactory::class,
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
            'middleware' => victorynox\AnalyticReports\Action\HelloAction::class,
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
            'name' => 'rest.api',
            'path' => '/rest[/{Resource-Name}[/{id}]]',
            'middleware' =>  zaboy\rest\Pipe\RestRql::class,
            'allowed_method' => ['GET', 'POST', 'DELETE', 'PUSH'],
        ],

        [
            'name' => 'notification',
            'path' => '/ebay/notification',
            'middleware' =>  victorynox\Notification\Action\NotificationAction::class,
            'allowed_method' => ['GET'],
        ],

        [
            'name' => 'notification.view',
            'path' => '/ebay/notifications/view',
            'middleware' =>  victorynox\Notification\Action\NotificationViewAction::class,
            'allowed_method' => ['GET'],
        ],

        /*[
            'name' => 'get.ebay.category',
            'path' => '/ebay/category',
            'middleware' =>  victorynox\Ebay\Category\Action\CategoryTreeAction::class,
            'allowed_method' => ['GET'],
        */

        [
            'name' => 'findItemByStore',
            'path' => '/ebay/find-item',
            'middleware' =>  victorynox\Ebay\Finding\Action\FindItemsIneBayStoreAction::class,
            'allowed_method' => ['GET'],
        ],

        [
            'name' => 'get.item.transactions',
            'path' => '/ebay/item-transactions',
            'middleware' =>  victorynox\Ebay\Trading\Action\GetItemTransactionsAction::class,
            'allowed_method' => ['GET'],
        ]

    ],
];
