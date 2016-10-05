<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 25.07.16
 * Time: 17:07
 */

namespace victorynox\Notification\DataStore\Factory;

use Interop\Container\ContainerInterface;
use zaboy\rest\DataStore\DataStoreException;
use zaboy\rest\DataStore\DbTable;
use zaboy\rest\DataStore\Factory\DbTableAbstractFactory;
use zaboy\rest\TableGateway\TableManagerMysql;
use Zend\Db\TableGateway\TableGateway;
use zaboy\rest\DataStore\DataStoreAbstract as DataStore;

class AllNotificationDataStoreFactory
{
    const KEY_ALL_NOTIFICATION = 'allNotification';
    const KEY_TABLE_NAME = 'tableName';
    const KEY_TABLE_GATEWAY = 'tableGateway';
    const KEY_DB_ADAPTER = 'dbAdapter';
    const KEY_DATASTORE = 'dataStore';
    const KEY_EBAY_NOTIFICATION_TYPE = 'type';
    const KEY_EBAY_NOTIFICATION_ADD_DATE = 'add_date';
    const KEY_EBAY_NOTIFICATION_DATA = 'data';

    protected $tablePreferenceTableData = [
        DataStore::DEF_ID => [
            'field_type' => 'Integer',
            'field_params' => [
                'nullable' => false,
                'options' => ['autoincrement' => true]
            ]
        ],

        self::KEY_EBAY_NOTIFICATION_ADD_DATE => [
            'field_type' => 'Datetime',
            'field_params' => [
                'nullable' => false
            ]
        ],

        self::KEY_EBAY_NOTIFICATION_TYPE => [
            'field_type' => 'Varchar',
            'field_params' => [
                'length' => 255,
                'nullable' => false
            ]
        ],

        self::KEY_EBAY_NOTIFICATION_DATA => [
            'field_type' => 'Blob',
            'field_params' => [
                'nullable' => false
            ]
        ]
    ];

    public function __invoke(ContainerInterface $container, $name, $requestedName)
    {
        $config = $container->get('config');
        $serviceConfig = $config[self::KEY_DATASTORE][self::KEY_ALL_NOTIFICATION];

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

            /*if ($container->has('TableManagerMysql')) {
                $tableManager = $container->get('TableManagerMysql');
            } else {*/
                $tableManager = new TableManagerMysql($db);
            /*}*/
            $hasTable = $tableManager->hasTable($tableName);

            if (!$hasTable) {
                $tableManager->rewriteTable($tableName, $this->tablePreferenceTableData);

            }
            $tableGateway = new TableGateway($tableName, $db);

        } else {
            throw new DataStoreException(
                'There is not table name for ' . self::KEY_ALL_NOTIFICATION . 'in config \'dataStore\''
            );
        }

        return new DbTable($tableGateway);
    }
}
