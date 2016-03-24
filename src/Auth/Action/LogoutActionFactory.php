<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 24.03.16
 * Time: 16:23
 */

namespace Auth\Action;

use Interop\Container\ContainerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class LogoutActionFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new LogoutAction($container->get(TemplateRendererInterface::class));
    }
}