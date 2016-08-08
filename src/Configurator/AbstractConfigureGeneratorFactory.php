<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 05.08.16
 * Time: 14:56
 */

namespace victorynox\Configurator;

use Interop\Container\ContainerInterface;
use victorynox\Configurator\Interfaces\ConfigureGeneratorFactoryInterface;
use zaboy\rest\AbstractFactoryAbstract;
use zaboy\rest\DataStore\Interfaces\DataStoresInterface;

class AbstractConfigureGeneratorFactory extends AbstractFactoryAbstract
{
    const KEY_CONFIGURE_GENERATOR_FACTORY = 'configureGeneratorFactory';

    /**
     * Can the factory create an instance for the service?
     *
     * For Service manager V3
     * Edit 'use' section if need:
     * Change:
     * 'use Zend\ServiceManager\AbstractFactoryInterface;' for V2 to
     * 'use Zend\ServiceManager\Factory\AbstractFactoryInterface;' for V3
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @return bool
     */
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        $config = $container->get("config");
        if (isset($config[self::KEY_CONFIGURE_GENERATOR_FACTORY][$requestedName])) {
            $factoryConfig = $config[self::KEY_CONFIGURE_GENERATOR_FACTORY][$requestedName];
            return isset($factoryConfig[self::KEY_CLASS])
            and is_a($factoryConfig[self::KEY_CLASS], ConfigureGeneratorFactoryInterface::class, true);
        }
        return false;
    }

    /**
     * Create and return an instance of the DataStore.
     *
     * 'use Zend\ServiceManager\AbstractFactoryInterface;' for V2 to
     * 'use Zend\ServiceManager\Factory\AbstractFactoryInterface;' for V3
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  array $options
     * @return DataStoresInterface
     * @throws \Exception
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get("config");
        $factoryConfig = $config["configureGeneratorFactory"][$requestedName];

        $class = $factoryConfig[self::KEY_CLASS];

        return new $class($container);
    }
}
