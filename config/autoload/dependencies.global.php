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
            'allNotification' => victorynox\DataStore\Notification\AllNotificationDataStoreFactory::class,
            'notificationDataSource' => victorynox\DataStore\Notification\DataSource\NotificationDataSourceFactory::class,
            'configurationTable' => 'victorynox\Configurator\ConfigurationTableFactory',
            'configuration' => 'victorynox\Configurator\DataStore\ConfigurationAspectFactory',
            'Zend\Session\Config\ConfigInterface' => 'Zend\Session\Service\SessionConfigFactory',
        ],
        'abstract_factories' => [
            'victorynox\DataStore\CDSAbstractFactory',
            'victorynox\AnalyticReports\DataStore\ReportsDSFactory',
            'zaboy\rest\DataStore\Aspect\Factory\AspectAbstractFactory',
            'zaboy\rest\Middleware\Factory\DataStoreAbstractFactory',
            'zaboy\rest\DataStore\Factory\HttpClientAbstractFactory',
            'zaboy\rest\DataStore\Factory\DbTableAbstractFactory',
            'zaboy\rest\DataStore\Factory\CsvAbstractFactory',
            'zaboy\rest\DataStore\Factory\MemoryAbstractFactory',
            'zaboy\rest\DataStore\Factory\CacheableAbstractFactory',
            'victorynox\DataStore\Notification\NotificationCacheableStoreFactory',
            'zaboy\rest\TableGateway\Factory\TableGatewayAbstractFactory',
            'Zend\Db\Adapter\AdapterAbstractServiceFactory',
            'victorynox\AnalyticReports\Middleware\CDSManagerAbstractFactory',
            'victorynox\Configurator\AbstractConfigureGeneratorFactory',
        ]
    ],
];
