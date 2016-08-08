<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 05.08.16
 * Time: 13:18
 */

namespace victorynox\Configurator;

use Interop\Container\ContainerInterface;
use victorynox\Configurator\Interfaces\ConfigureGeneratorFactoryInterface;

class ConfigureGeneratorFactory implements ConfigureGeneratorFactoryInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke($requestedName)
    {
        if (!$this->container->has($requestedName)) {
            throw new \Exception("DataStore: ". $requestedName ." not found in container");
        }
        $dataStore = $this->container->get($requestedName);
        return new ConfigureGenerator($dataStore);
    }
}
