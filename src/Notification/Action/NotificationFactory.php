<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14.04.16
 * Time: 14:06
 */

namespace victorynox\Notification\Action;

use Interop\Container\ContainerInterface;

class NotificationFactory
{
    public function __invoke(ContainerInterface $container, $requestedName)
    {
        $config = $container->get('config');
        $store = $container->has('ebayNotification') ? $container->get('ebayNotification') : null;
        return new NotificationAction($config['ebay'], $store);
    }
}
