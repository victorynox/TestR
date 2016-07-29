<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 25.07.16
 * Time: 15:54
 */

namespace victorynox\DataStore\TablePreferenceList;

use Interop\Container\ContainerInterface;
use zaboy\rest\DataStore\DataStoreException;
use zaboy\rest\DataStore\DbTable;
use zaboy\rest\DataStore\Factory\DbTableAbstractFactory;
use zaboy\rest\DataStore\Interfaces\DataStoresInterface;
use zaboy\rest\TableGateway\TableManagerMysql;
use Zend\Db\TableGateway\TableGateway;

class TablePreferenceDbTableFactory
{

    static $KEY_TABLE_PREFERENCE_LIST = 'tablePreferenceList';

    const KEY_TABLE_NAME = 'tableName';
    const KEY_TABLE_GATEWAY = 'tableGateway';
    const KEY_DB_ADAPTER = 'dbAdapter';
    const KEY_DATASTORE = 'dataStore';

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
        $serviceConfig = $config[self::KEY_DATASTORE][$requestedName];

        if (isset($serviceConfig[self::KEY_TABLE_GATEWAY])) {
            if ($container->has($serviceConfig[self::KEY_TABLE_GATEWAY])) {
                $tableGateway = $container->get($serviceConfig[self::KEY_TABLE_GATEWAY]);
            } else {
                throw new DataStoreException(
                    'Can\'t create ' . $serviceConfig[self::KEY_TABLE_GATEWAY]
                );
            }
        } else if (isset($serviceConfig[self::KEY_TABLE_NAME])) {
            $tableName = $serviceConfig[self::KEY_TABLE_NAME];

            $dbServiceName = isset($serviceConfig[self::KEY_DB_ADAPTER]) ? $serviceConfig[self::KEY_DB_ADAPTER] : 'db';
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

        return new DbTable($tableGateway);
    }
}
