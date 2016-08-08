<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 05.08.16
 * Time: 15:50
 */

namespace victorynox\Configurator;

use Interop\Container\ContainerInterface;
use zaboy\rest\AbstractFactoryAbstract;
use zaboy\rest\DataStore\DataStoreException;
use zaboy\rest\DataStore\DbTable;
use zaboy\rest\DataStore\Factory\AbstractDataStoreFactory;
use zaboy\rest\DataStore\Factory\DbTableAbstractFactory;
use zaboy\rest\TableGateway\TableManagerMysql;
use Zend\Db\TableGateway\TableGateway;

class ConfigurationTableFactory
{
    static $KEY_CONFIGURATION_TABLE = 'configurationTable';

    protected $tablePreferenceTableData = [
        'id' => [
            'field_type' => 'Integer',
            'field_params' => [
                'length' => 128,
                'nullable' => false,
                'options' => ['autoincrement' => true]
            ]
        ],
        'name' => [
            'field_type' => 'Varchar',
            'field_params' => [
                'length' => 255,
                'nullable' => false
            ]
        ],
        'table_name' => [
            'field_type' => 'Varchar',
            'field_params' => [
                'length' => 255,
                'nullable' => false
            ]
        ],
        'preference' => [
            'field_type' => 'Blob',
            'field_params' => [
                'nullable' => false
            ]
        ]
    ];

    public function __invoke(ContainerInterface $container, $reqName, $requestedName)
    {
        $config = $container->get('config');
        $serviceConfig = $config[AbstractDataStoreFactory::KEY_DATASTORE][$requestedName];

        if (isset($serviceConfig[DbTableAbstractFactory::KEY_TABLE_GATEWAY])) {
            if ($container->has($serviceConfig[DbTableAbstractFactory::KEY_TABLE_GATEWAY])) {
                $tableGateway = $container->get($serviceConfig[DbTableAbstractFactory::KEY_TABLE_GATEWAY]);
            } else {
                throw new DataStoreException(
                    'Can\'t create ' . $serviceConfig[DbTableAbstractFactory::KEY_TABLE_GATEWAY]
                );
            }
        } else if (isset($serviceConfig[DbTableAbstractFactory::KEY_TABLE_NAME])) {
            $tableName = $serviceConfig[DbTableAbstractFactory::KEY_TABLE_NAME];

            $dbServiceName = isset($serviceConfig[DbTableAbstractFactory::KEY_DB_ADAPTER]) ?
                $serviceConfig[DbTableAbstractFactory::KEY_DB_ADAPTER] : 'db';
            $db = $container->has($dbServiceName) ? $container->get($dbServiceName) : null;

            if ($container->has('TableManagerMysql')) {
                $tableManager = $container->get('TableManagerMysql');
            } else {
                $tableManager = new TableManagerMysql($db);
            }
            $hasTable = $tableManager->hasTable($tableName);

            if (!$hasTable) {
                $tableManager->rewriteTable($tableName, $this->tablePreferenceTableData);
            }
            $tableGateway = new TableGateway($tableName, $db);

        } else {
            throw new DataStoreException(
                'There is not table name for ' . $requestedName . 'in config \'dataStore\''
            );
        }

        $class = (isset($serviceConfig[AbstractFactoryAbstract::KEY_CLASS])
        and is_a($serviceConfig[AbstractFactoryAbstract::KEY_CLASS], DbTable::class, true)) ?
            $serviceConfig[AbstractFactoryAbstract::KEY_CLASS] : DbTable::class;

        return new $class($tableGateway);
    }
}
