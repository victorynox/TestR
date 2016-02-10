<?php
/**
 * Created by PhpStorm.
 * User: victorynox
 * Date: 10.02.16
 * Time: 16:41
 */

namespace App\Action;

use Interop\Container\ContainerInterface;
use Zend\Stratigility\MiddlewarePipe;


class ApiResourcePipelineFactory
{

    public function __invoke(ContainerInterface $container)
    {

        $pipeline = new MiddlewarePipe();
        $pipeline->pipe($container->get('RScriptMiddleware'));

        return $pipeline;

    }

}