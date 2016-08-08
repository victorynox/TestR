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
            'class' => 'victorynox\AnalyticReports\Middleware\CDSManagerMiddleware',
            'cdsManagerStore' => 'cdsManagerDbTable',
            'dataStore' => 'rHttpClient',
            'cds' => 'cachingDbTable',
        ]
    ],

    'tableGateway' => [
        'cds_table' => [
            'sql' => 'zaboy\rest\TableGateway\DbSql\MultiInsertSql'
        ]
    ],

    'dataStore' => [


        //*********************************
        'cachingDataStore' => [
            'class' => 'victorynox\DataStore\CachingDataStore',
            'cachingDbTable' => 'cachingDbTable'
        ],

        'cachingDbTable' => [
            'class' => 'zaboy\rest\DataStore\DbTable',
            'tableGateway' => 'cds_table'
        ],

        'cdsManagerDbTable' => [
            'class' => 'zaboy\rest\DataStore\DbTable',
            'tableName' => 'cds_manager'
        ],

        'rHttpClient' => [
            'class' => 'zaboy\rest\DataStore\HttpClient',
            'url' => 'http://192.168.122.22:11999/',
            #'url' => 'http://localhost:11999/',
            'options' => ['timeout' => 30]
        ],

        'getCategory' => [
            'class' => 'victorynox\DataStore\GetCategoryAspect',
            'dataStore' => 'rHttpClient',
        ],

        'getBrand' => [
            'class' => 'victorynox\DataStore\GetBrandAspect',
            'dataStore' => 'rHttpClient',
        ],

        //*********************************

        'ebay-category' => [
            'class' => 'zaboy\rest\DataStore\HttpClient',
            #'url' => 'http://192.168.122.22:7080/',
            'url' => 'http://localhost:7080/',
        ],

        'filtersList' => [
            'class' => 'zaboy\rest\DataStore\DbTable',
            'tableName' => 'filters_list'
        ],

        'allNotification' => [
            'tableName' => 'ebay_notification'
        ],

        'notificationCacheable' => [
            'dataSource' => 'notificationDataSource',
            'class' => 'victorynox\DataStore\Notification\NotificationCacheable'
        ],

        # Notification CacheableDS

        'NotificationItemListed' => [
            'class' => 'victorynox\DataStore\Notification\NotificationTypeDataStore\ItemListedDataStore'
        ],
        'NotificationTypeNotification' => [
            'class' => 'victorynox\DataStore\Notification\NotificationTypeDataStore\TypeNotificationDataStore'
        ],

        'typeNotification' => [
            'dataSource' => 'typeNotificationDataSource',
            'class' => 'victorynox\DataStore\Notification\NotificationCacheable'
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
            'class' => 'victorynox\Configurator\ConfigureGeneratorFactory',
        ]
    ]
];