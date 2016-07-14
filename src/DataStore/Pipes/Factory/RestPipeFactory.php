<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 28.03.16
 * Time: 15:18
 */

namespace victorynox\DataStore\Pipes\Factory;

use Interop\Container\ContainerInterface;
use zaboy\rest\Pipe\Factory\RestRqlFactory;

class RestPipeFactory
{
    public function __invoke(ContainerInterface $container, $requestedName)
    {
        $pipeFactory =  new RestRqlFactory();
        return $pipeFactory($container, $requestedName, []);
    }
}