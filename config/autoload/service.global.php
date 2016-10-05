<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 15.07.16
 * Time: 13:05
 */

return [
    'dataSource' => [
        'typeNotificationDataSource' => [
            'dataStore' => 'allNotification'
        ],

        'notificationDataSource' => [
            'dataStore' => 'allNotification'
        ]
    ],

    'middleware' => [
        'cdsManager' => [
            'class' => victorynox\AnalyticReports\Middleware\CDSManagerMiddleware::class,
            'cdsManagerStore' => 'cdsManagerDbTable',
            'dataStore' => 'rHttpClient',
            'cds' => 'cachingDbTable',
        ]
    ],

    'tableGateway' => [
        'cds_table' => [
            'sql' => zaboy\rest\TableGateway\DbSql\MultiInsertSql::class
        ]
    ],

    'dataStore' => [


        //*********************************
        'cachingDataStore' => [
            'class' => victorynox\AnalyticReports\DataStore\CachingDataStore::class,
            'cachingDbTable' => 'cachingDbTable'
        ],

        'cachingDbTable' => [
            'class' => zaboy\rest\DataStore\DbTable::class,
            'tableGateway' => 'cds_table'
        ],

        'cdsManagerDbTable' => [
            'class' => zaboy\rest\DataStore\DbTable::class,
            'tableName' => 'cds_manager'
        ],

        'rHttpClient' => [
            'class' => zaboy\rest\DataStore\HttpClient::class,
            'url' => 'http://192.168.122.22:11999/',
            #'url' => 'http://localhost:11999/',
            'options' => ['timeout' => 30]
        ],

        'getCategory' => [
            'class' => victorynox\AnalyticReports\DataStore\GetCategoryAspect::class,
            'dataStore' => 'rHttpClient',
        ],

        'getBrand' => [
            'class' => victorynox\AnalyticReports\DataStore\GetBrandAspect::class,
            'dataStore' => 'rHttpClient',
        ],

        //*********************************

        'ebay-category' => [
            'class' => zaboy\rest\DataStore\HttpClient::class,
            #'url' => 'http://192.168.122.22:7080/',
            'url' => 'http://localhost:7080/',
        ],

        'filtersList' => [
            'class' => zaboy\rest\DataStore\DbTable::class,
            'tableName' => 'filters_list'
        ],

        'allNotification' => [
            'tableName' => 'ebay_notification'
        ],

        'notificationCacheable' => [
            'dataSource' => 'notificationDataSource',
            'class' => victorynox\Notification\DataStore\NotificationCacheable::class
        ],

        # Notification CacheableDS

        'NotificationItemListed' => [
            'class' => victorynox\Notification\DataStore\NotificationType\ItemListedDataStore::class
        ],
        'NotificationAuctionCheckoutComplete' => [
            'class' => victorynox\Notification\DataStore\NotificationType\AuctionCheckoutCompleteDataStore::class
        ],
        'NotificationTypeNotification' => [
            'class' => victorynox\Notification\DataStore\NotificationType\TypeNotificationDataStore::class
        ],

        'typeNotification' => [
            'dataSource' => 'typeNotificationDataSource',
            'class' => victorynox\Notification\DataStore\NotificationCacheable::class
        ],

        "configurationTable" => [
            'tableName' => 'table_preference'
        ],

        'configuration' => [
            'dataStore' => 'configurationTable',
            'configureGeneratorFactory' => 'configGenFactory'
        ]

    ],

    'configureGeneratorFactory' => [
        'configGenFactory' => [
            'class' => victorynox\Configurator\ConfigureGeneratorFactory::class,
        ]
    ]
];