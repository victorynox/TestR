<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 27.07.16
 * Time: 15:43
 */

namespace victorynox\DataStore\Notification\DataSource;

use Interop\Container\ContainerInterface;

class NotificationDataSourceFactory
{
    public function __invoke(ContainerInterface $container, $reqName, $requestedName)
    {
        $config = $container->get('config');
        $dataSourceConfig = $config['dataSource'][$requestedName];

        if (!isset($dataSourceConfig['dataStore'])) {
            throw  new \Exception("dataStore not set");
        }

        $allNotification = $container->get($dataSourceConfig['dataStore']);

        return new NotificationDataSource($allNotification);
    }
}
