<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 10.07.16
 * Time: 11:30
 */

namespace victorynox\AnalyticReports\Middleware;

use Interop\Container\ContainerInterface;
use zaboy\rest\AbstractFactoryAbstract;

class CDSManagerAbstractFactory extends AbstractFactoryAbstract
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');

        if (!isset($config['middleware'][$requestedName])) {
            throw new \Exception("Not config for cdsManager");
        }

        $cdsManagerConf = $config['middleware'][$requestedName];

        if (!isset($cdsManagerConf['class']) ||
            !isset($cdsManagerConf['cdsManagerStore']) ||
            !isset($cdsManagerConf['dataStore']) ||
            !isset($cdsManagerConf['cds'])
        ) {
            throw new \Exception("Not config for cdsManager");
        }

        $class = $cdsManagerConf['class'];

        if (!$container->has($cdsManagerConf['cdsManagerStore']) ||
            !$container->has($cdsManagerConf['dataStore']) ||
            !$container->has($cdsManagerConf['cds'])
        ) {
            throw new \Exception("Not config for cdsManager");
        }

        $cdsManagerStore = $container->get($cdsManagerConf['cdsManagerStore']);
        $dataStore = $container->get($cdsManagerConf['dataStore']);
        $cds = $container->get($cdsManagerConf['cds']);

        return new $class($dataStore, $cdsManagerStore, $cds);
    }

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
     * @throws \Exception
     */
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        $config = $container->get('config');

        if (isset($config['middleware'][$requestedName])) {
            $cdsManagerConf = $config['middleware'][$requestedName];
            if (isset($cdsManagerConf['class'])) {
                return is_a($cdsManagerConf['class'], 'victorynox\AnalyticReports\Middleware\CDSManagerMiddleware', true);
            }
        }

        return false;
    }
}
