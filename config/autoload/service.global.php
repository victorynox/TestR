<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 15.07.16
 * Time: 13:05
 */

return [
    'dataSource' => [
        'plotPublishPrice' => ['url' => 'http://192.168.122.22:11999/'],
        'getCategory' => ['url' => 'http://192.168.122.22:11999/'],
        'getBrand' => ['url' => 'http://192.168.122.22:11999/'],
    ],

    'middleware' => [
        'cdsManager' => [
            'class' => 'victorynox\AnalyticReports\Middleware\CDSManagerMiddleware',
            'cdsManagerStore' => 'cdsManagerDbTable',
            'dataStore' => 'rHttpClient',
            'cds' => 'cachingDbTable',
            'allowResource' => [
                "plotPublishPrice",
                "plotSoldPrice",
                "plotProbPrice",
                "plotProfPrice",
                "plotPublishDay",
                "plotSoldDay",
                "plotProbDay",
                "plotCreatedDay",
                "plotPublishTime",
                "plotSoldTime",
                "plotProbTime",
                "plotCreatedTime",
                "plotCreatedTimeWithTZ",
                "tableCategoryPrice",
                "tableCategory",
                "tableProduct",
                "tableModel",
                "tableProductModel",
                "bestProducts",
                "bestCompetitor",
                "NN",
                "NNSold"
            ]
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
            'tableName' => 'cds_table'
        ],

        'cdsManagerDbTable' => [
            'class' => 'zaboy\rest\DataStore\DbTable',
            'tableName' => 'cds_manager'
        ],

        'rHttpClient' => [
            'class' => 'zaboy\rest\DataStore\HttpClient',
            'url' => 'http://192.168.122.22:11999/',
            'options' => ['timeout' => 30]
        ],
        //StaticDataStore
        'rServer' => [
            'class' => 'zaboy\rest\DataStore\HttpClient',
            'url' => 'http://192.168.122.22:11999/',
        ],


        'getCategory' => [
            'class' => 'victorynox\DataStore\GetCategoryAspect',
            'dataStore' => 'rServer',
        ],

        'getBrand' => [
            'class' => 'victorynox\DataStore\GetBrandAspect',
            'dataStore' => 'rServer',
        ],

        //*********************************

        'ebay-category' => [
            'class' => 'zaboy\rest\DataStore\HttpClient',
            #'url' => 'http://192.168.122.22:7080/',
            'url' => 'http://localhost:7080/',
        ],

        'ebay_notification' => [
            'class' => 'zaboy\rest\DataStore\DbTable',
            'tableName' => 'ebay_notification'
        ],

        'filters_list' => [
            'class' => 'zaboy\rest\DataStore\DbTable',
            'tableName' => 'filters_list'
        ],

        'table_preference' => [
            'class' => 'zaboy\rest\DataStore\DbTable',
            'tableName' => 'table_preference'
        ]

    ],
];