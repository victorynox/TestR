<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 29.07.16
 * Time: 17:39
 */

namespace victorynox\Notification\DataSource;

use Interop\Container\ContainerInterface;

class TypeNotificationFactory
{
    public function __invoke(ContainerInterface $container, $reqName, $requestedName)
    {
        $config = $container->get('config');
        $dataSourceConfig = $config['dataSource'][$requestedName];

        if (!isset($dataSourceConfig['dataStore'])) {
            throw  new \Exception("dataStore not set");
        }

        $allNotification = $container->get($dataSourceConfig['dataStore']);

        return new TypeNotificationDataSource($allNotification);
    }
}
