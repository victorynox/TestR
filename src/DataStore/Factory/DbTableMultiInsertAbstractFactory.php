<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 21.07.16
 * Time: 12:42
 */

namespace victorynox\DataStore\Factory;

use Interop\Container\ContainerInterface;
use victorynox\DataStore\MultiInsertSql;
use zaboy\rest\DataStore\DataStoreException;
use zaboy\rest\DataStore\Factory\DbTableAbstractFactory;
use Zend\Db\TableGateway\TableGateway;

class DbTableMultiInsertAbstractFactory extends  DbTableAbstractFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');
        $serviceConfig = $config['dataStore'][$requestedName];
        $requestedClassName = $serviceConfig['class'];
        if (isset($serviceConfig['tableName'])) {
            $tableName = $serviceConfig['tableName'];
        } else {
            throw new DataStoreException(
                'There is not table name for ' . $requestedName . 'in config \'dataStore\''
            );
        }
        $dbServiceName = isset($serviceConfig['dbAdapter']) ? $serviceConfig['dbAdapter'] : 'db';
        $db = $container->has($dbServiceName) ? $container->get($dbServiceName) : null;
        if (null !== $db) {
            $sql = new MultiInsertSql($db, $tableName);
            $tableGateway = new TableGateway($tableName, $db, null, null, $sql);
        } else {
            throw new DataStoreException(
                'Can\'t create Zend\Db\TableGateway\TableGateway for ' . $tableName
            );
        }
        return new $requestedClassName($tableGateway);
    }
}
