<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 10.07.16
 * Time: 15:03
 */

namespace victorynox\AnalyticReports\DataStore;

use Interop\Container\ContainerInterface;
use victorynox\AnalyticReports\Middleware\CDSManagerMiddleware;
use zaboy\rest\AbstractFactoryAbstract;
use zaboy\rest\DataStore\DataStoreException;
use zaboy\rest\DataStore\Interfaces\DataStoresInterface;

class CDSAbstractFactory extends AbstractFactoryAbstract
{

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
     * @throws DataStoreException
     */
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        if (!$container->has('cdsManager')) {
            throw new DataStoreException('cdsManager not found');
        }
        /** @var CDSManagerMiddleware $cdsManager */
        $cdsManager = $container->get('cdsManager');

        return $cdsManager->checkCds($requestedName);
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
     * @throws DataStoreException
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        //todo add using class in config

        $config = $container->get('config');
        if (!isset($config['dataStore']['cachingDataStore'])) {
            throw new DataStoreException('cachingDataStore not found');
        }

        $cdsConfig = $config['dataStore']['cachingDataStore'];

        if ($container->get($cdsConfig['cachingDbTable'])) {
            $cachingDbTable = $container->get($cdsConfig['cachingDbTable']);
        } else {
            throw new DataStoreException('cachingDbTable not found');
        }

        return new CachingDataStore($cachingDbTable, $requestedName);
    }
}
