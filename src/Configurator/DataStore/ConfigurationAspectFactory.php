<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 05.08.16
 * Time: 14:14
 */

namespace victorynox\Configurator\DataStore;

use Interop\Container\ContainerInterface;
use victorynox\Configurator\AbstractConfigureGeneratorFactory;
use zaboy\rest\AbstractFactoryAbstract;
use zaboy\rest\DataStore\DataStoreException;
use zaboy\rest\DataStore\Factory\AbstractDataStoreFactory;

class ConfigurationAspectFactory
{
    const KEY_CONFIGURATION = "configuration";

    /**
     * @param ContainerInterface $container
     * @return ConfigurationAspect
     * @throws DataStoreException
     */
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get("config");
        $serviceConfig = $config[AbstractDataStoreFactory::KEY_DATASTORE][self::KEY_CONFIGURATION];
        if (!$container->has($serviceConfig[AbstractDataStoreFactory::KEY_DATASTORE])) {
            throw new DataStoreException("DataStore `". $serviceConfig[AbstractDataStoreFactory::KEY_DATASTORE] . "` no fount in container");
        }

        $dataStore = $container->get($serviceConfig[AbstractDataStoreFactory::KEY_DATASTORE]);

        if (!$container->has($serviceConfig[AbstractConfigureGeneratorFactory::KEY_CONFIGURE_GENERATOR_FACTORY])) {
            throw new DataStoreException("configureGeneratorFactory `". $serviceConfig[AbstractConfigureGeneratorFactory::KEY_CONFIGURE_GENERATOR_FACTORY] . "` no fount in container");
        }

        $configureGeneratorFactory = $container->get($serviceConfig[AbstractConfigureGeneratorFactory::KEY_CONFIGURE_GENERATOR_FACTORY]);

        $class = isset($serviceConfig[AbstractFactoryAbstract::KEY_CLASS]) ? $serviceConfig[AbstractFactoryAbstract::KEY_CLASS] : ConfigurationAspect::class;

        if(!is_a($class,ConfigurationAspect::class, true)){
            throw new DataStoreException($serviceConfig[AbstractFactoryAbstract::KEY_CLASS] ." is not instanceof  ConfigurationAspect");
        }

        return new $class($dataStore, $configureGeneratorFactory);
    }


}
