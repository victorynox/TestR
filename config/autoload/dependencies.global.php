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
            'allNotification' => victorynox\Notification\DataStore\Factory\AllNotificationDataStoreFactory::class,
            'notificationDataSource' => victorynox\Notification\DataSource\NotificationDataSourceFactory::class,
            'typeNotificationDataSource' => victorynox\Notification\DataSource\TypeNotificationFactory::class,
            'configurationTable' => victorynox\Configurator\DataStore\Factory\ConfigurationTableFactory::class,
            'configuration' => victorynox\Configurator\DataStore\Factory\ConfigurationAspectFactory::class,
            Zend\Session\Config\ConfigInterface::class => Zend\Session\Service\SessionConfigFactory::class,
        ],

        'abstract_factories' => [
            victorynox\AnalyticReports\DataStore\CDSAbstractFactory::class,
            victorynox\AnalyticReports\DataStore\ReportsDSFactory::class,
            zaboy\rest\DataStore\Aspect\Factory\AspectAbstractFactory::class,
            zaboy\rest\Middleware\Factory\DataStoreAbstractFactory::class,
            zaboy\rest\DataStore\Factory\HttpClientAbstractFactory::class,
            zaboy\rest\DataStore\Factory\DbTableAbstractFactory::class,
            zaboy\rest\DataStore\Factory\CsvAbstractFactory::class,
            zaboy\rest\DataStore\Factory\MemoryAbstractFactory::class,
            zaboy\rest\DataStore\Factory\CacheableAbstractFactory::class,
            victorynox\Notification\DataStore\Factory\NotificationCacheableStoreFactory::class,
            zaboy\rest\TableGateway\Factory\TableGatewayAbstractFactory::class,
            Zend\Db\Adapter\AdapterAbstractServiceFactory::class,
            victorynox\AnalyticReports\Middleware\CDSManagerAbstractFactory::class,
            victorynox\Configurator\AbstractConfigureGeneratorFactory::class
        ]
    ],
];
