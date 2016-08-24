<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 29.07.16
 * Time: 17:27
 */

namespace victorynox\Notification\DataSource;

use victorynox\Notification\DataStore\Factory\AllNotificationDataStoreFactory;
use Xiag\Rql\Parser\Query;
use zaboy\rest\DataStore\Interfaces\DataSourceInterface;
use zaboy\rest\DataStore\Interfaces\DataStoresInterface;

class TypeNotificationDataSource implements DataSourceInterface
{
    protected $dataStore;

    const KEY_COUNT_NOTIFICATION_TYPE = 'count';

    public function __construct(DataStoresInterface $dataStores)
    {
        $this->dataStore = $dataStores;
    }

    /**
     * @return array Return data of DataSource
     */
    public function getAll()
    {
        $formatedData = [];
        $data = $this->dataStore->query(new Query());
        foreach ($data as $item){
            if(isset($formatedData[$item[AllNotificationDataStoreFactory::KEY_EBAY_NOTIFICATION_TYPE]])){
                $formatedData[$item[AllNotificationDataStoreFactory::KEY_EBAY_NOTIFICATION_TYPE]] += 1;
            }else {
                $formatedData[$item[AllNotificationDataStoreFactory::KEY_EBAY_NOTIFICATION_TYPE]] = 1;
            }
        }
        $notificationType= [];

        $i = 0;
        foreach ($formatedData as $key => $value){
            $notificationType[] = [
                'id' => $i,
                AllNotificationDataStoreFactory::KEY_EBAY_NOTIFICATION_TYPE => $key,
                self::KEY_COUNT_NOTIFICATION_TYPE => $value,
            ];
            $i++;
        }

        return $notificationType;
    }
}
