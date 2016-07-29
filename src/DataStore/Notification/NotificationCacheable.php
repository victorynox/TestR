<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 25.07.16
 * Time: 15:41
 */

namespace victorynox\DataStore\Notification;

use zaboy\rest\DataStore\Cacheable;
use zaboy\rest\DataStore\Interfaces\DataSourceInterface;
use zaboy\rest\DataStore\Interfaces\DataStoresInterface;

class NotificationCacheable extends Cacheable {

    public function __construct(DataSourceInterface $dataSource, DataStoresInterface $cashStore = null)
    {
        parent::__construct($dataSource, $cashStore);
        $this->refresh();
    }

    public function getTableConfig(){
        if(method_exists($this->cashStore, 'getTableConfig')){
            return $this->cashStore->getTableConfig();
        }
        return null;
    }
}
