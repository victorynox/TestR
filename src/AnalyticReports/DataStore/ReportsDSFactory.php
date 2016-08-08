<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 08.08.16
 * Time: 14:07
 */

namespace victorynox\AnalyticReports\DataStore;

use Interop\Container\ContainerInterface;
use victorynox\AnalyticReports\Middleware\CDSManagerMiddleware;
use victorynox\DataStore\CachingDataStore;
use victorynox\DataStore\CDSAbstractFactory;
use zaboy\rest\DataStore\DataStoreException;

class ReportsDSFactory extends CDSAbstractFactory
{
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        if (!$container->has('cdsManager')) {
            throw new DataStoreException('cdsManager not found');
        }
        /** @var CDSManagerMiddleware $cdsManager */
        $cdsManager = $container->get('cdsManager');
        $cdsId = $cdsManager->generateCsdId($requestedName, '');
        return $cdsManager->checkCds($cdsId);
    }


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

        /** @var CDSManagerMiddleware $cdsManager */
        $cdsManager = $container->get('cdsManager');

        $cdsId = $cdsManager->generateCsdId($requestedName, '');
        return new CachingDataStore($cachingDbTable, $cdsId);
    }
}
