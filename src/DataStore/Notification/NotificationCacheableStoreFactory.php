<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 25.07.16
 * Time: 17:08
 */

namespace victorynox\DataStore\Notification;

use Interop\Container\ContainerInterface;
use Xiag\Rql\Parser\Node\Query\ScalarOperator\EqNode;
use Xiag\Rql\Parser\Query;
use zaboy\rest\DataStore\DataStoreException;
use zaboy\rest\DataStore\Factory\CacheableAbstractFactory;
use zaboy\rest\DataStore\Interfaces\DataStoresInterface;

class NotificationCacheableStoreFactory extends CacheableAbstractFactory
{
    const KEY_ALL_NOTIFICATION = 'allNotification';
    const KEY_CACHEABLE_NOTIFICATION = 'notificationCacheable';

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');
        $serviceConfig = $config[self::KEY_DATASTORE][self::KEY_CACHEABLE_NOTIFICATION];
        $requestedClassName = $serviceConfig[self::KEY_CLASS];
        if (isset($serviceConfig[self::KEY_DATASOURCE])) {
            if ($container->has($serviceConfig[self::KEY_DATASOURCE])) {
                $getAll = $container->get($serviceConfig[self::KEY_DATASOURCE]);
                $getAll->setNotificationType($requestedName);
            } else {
                throw new DataStoreException(
                    'There is DataSource not created ' . $requestedName . 'in config \'dataStore\''
                );
            }
        } else {
            throw new DataStoreException(
                'There is DataSource for ' . $requestedName . 'in config \'dataStore\''
            );
        }
        $serviceConfig[self::KEY_CACHEABLE] = 'Notification' . $requestedName;

        if ($container->has($serviceConfig[self::KEY_CACHEABLE])) {
            $cashStore = $container->get($serviceConfig[self::KEY_CACHEABLE]);
        } else {
            throw new DataStoreException(
                'There is DataSource for ' . $serviceConfig[self::KEY_CACHEABLE] . 'in config \'dataStore\''
            );
        }

        //$cashStore = isset($serviceConfig['cashStore']) ?  new $serviceConfig['cashStore']() : null;
        return new $requestedClassName($getAll, $cashStore);
    }

    public function canCreate(ContainerInterface $container, $requestedName)
    {
        /** @var DataStoresInterface $allNotification */
        $allNotification = $container->get($this::KEY_ALL_NOTIFICATION);
        $query = new Query();
        $query->setQuery(new EqNode(AllNotificationDataStoreFactory::KEY_EBAY_NOTIFICATION_TYPE, $requestedName));
        $result = $allNotification->query($query);

        return count($result) > 0;
    }


}
