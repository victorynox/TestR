<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 21.04.16
 * Time: 15:23
 */

namespace victorynox\Ebay\Notification\Action;

use Interop\Container\ContainerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class NotificationViewFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new NotificationViewAction($container->get(TemplateRendererInterface::class));
    }
}
