<?php
/**
 * Created by PhpStorm.
 * User: victorynox
 * Date: 11.02.16
 * Time: 14:50
 */

namespace App\Middleware\Factory;
use App\Middleware\RScriptValidatorMiddleware;
use Interop\Container\ContainerInterface;

class RScriptValidatorFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new RScriptValidatorMiddleware($container->get('config'));
    }

}