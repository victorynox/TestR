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

    /**
     * @param ContainerInterface $container
     * @return AuthenticationMiddleware
     */
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');
        $authConfig = $config['authentications'];
        return new AuthenticationMiddleware($container->get(TemplateRendererInterface::class), $authConfig);
    }
}
