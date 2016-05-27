<?php
/**
 * Created by PhpStorm.
 * User: victorynox
 * Date: 12.02.16
 * Time: 14:27
 */

namespace victorynox\Auth\Middleware;

use Interop\Container\ContainerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class AuthenticationFactory
{

    public function __invoke(ContainerInterface $container)
    {
        return new AuthenticationMiddleware($container->get(TemplateRendererInterface::class), $container->get('config'));
    }
}
