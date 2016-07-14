<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 22.04.16
 * Time: 17:57
 */

namespace victorynox\Ebay\Notification\Action;

use Interop\Container\ContainerInterface;

class GetNotificationDataFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new GetNotificationDataAction($container->get('ebay_notification'));
    }
}
