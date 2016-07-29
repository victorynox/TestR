<?php
use Zend\Expressive\Application;
use Zend\Expressive\Container\ApplicationFactory;
use Zend\Expressive\Helper;

return [
    // Provides application-wide services.
    // We recommend using fully-qualified class names whenever possible as
    // service names.
    'dependencies' => [
        // Use 'invokables' for constructor-less services, or services that do
        // not require arguments to the constructor. Map a service name to the
        // class name.
        'invokables' => [
            // Fully\Qualified\InterfaceName::class => Fully\Qualified\ClassName::class,
            Helper\ServerUrlHelper::class => Helper\ServerUrlHelper::class,
        ],
        // Use 'factories' for services provided by callbacks/factory classes.
        'factories' => [
            Application::class => ApplicationFactory::class,
            Helper\UrlHelper::class => Helper\UrlHelperFactory::class,
            'tablePreferenceRest' => victorynox\DataStore\TablePreferenceList\TablePreferenceDataStoreRestFactory::class,
            'tablePreferenceListDbTable' => victorynox\DataStore\TablePreferenceList\TablePreferenceDbTableFactory::class,
            'allNotification' => victorynox\DataStore\Notification\AllNotificationDataStoreFactory::class,
            'notificationDataSource' => victorynox\DataStore\Notification\DataSource\NotificationDataSourceFactory::class,
            'typeNotificationDataSource' => victorynox\DataStore\Notification\DataSource\TypeNotificationFactory::class,
        ],
        'abstract_factories' => [
            'victorynox\DataStore\CDSAbstractFactory',
            'zaboy\rest\DataStore\Aspect\Factory\AspectAbstractFactory',
            'zaboy\rest\Middleware\Factory\DataStoreAbstractFactory',
            'zaboy\rest\DataStore\Factory\HttpClientAbstractFactory',
            'zaboy\rest\DataStore\Factory\DbTableAbstractFactory',
            'zaboy\rest\DataStore\Factory\CsvAbstractFactory',
            'zaboy\rest\DataStore\Factory\MemoryAbstractFactory',
            'victorynox\DataStore\Notification\NotificationCacheableStoreFactory',
            //'victorynox\DataStore\Notification\NotificationTypeDataStore\NotificationTypeDataStoreFactory',
            'zaboy\rest\DataStore\Factory\CacheableAbstractFactory',
            'zaboy\rest\TableGateway\Factory\TableGatewayAbstractFactory',
            'Zend\Db\Adapter\AdapterAbstractServiceFactory',
            'victorynox\AnalyticReports\Middleware\CDSManagerAbstractFactory',
        ]
    ],
];
