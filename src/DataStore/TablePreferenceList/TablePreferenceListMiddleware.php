<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 01.08.16
 * Time: 13:39
 */

namespace victorynox\DataStore\TablePreferenceList;
use zaboy\rest\DataStore\Interfaces\DataStoresInterface;
use zaboy\rest\Middleware;

class TablePreferenceListMiddleware extends Middleware\DataStoreRest
{

    /**
     * @param string $tableName
     */
    public function setTableName($tableName)
    {
        if(method_exists($this->dataStore, 'setTableName')) {
            $this->dataStore->setTableName($tableName);
        }
    }

    public function setTableDS(DataStoresInterface $tableDS)
    {
        if(method_exists($this->dataStore, 'setTableDS')){
            $this->dataStore->setTableDS($tableDS);
        }
    }
}
