<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 25.07.16
 * Time: 17:45
 */

namespace victorynox\Notification\DataSource;

use victorynox\Notification\DataStore\Factory\AllNotificationDataStoreFactory;
use Xiag\Rql\Parser\Node\Query\ScalarOperator\EqNode;
use Xiag\Rql\Parser\Query;
use zaboy\rest\DataStore\Interfaces\DataSourceInterface;
use zaboy\rest\DataStore\Interfaces\DataStoresInterface;

class NotificationDataSource implements DataSourceInterface
{
    /** @var  DataStoresInterface */
    protected $dataStore;

    protected $notificationType;

    protected $regParserData = [
        'ItemListed' => [
            'regExp' => '/\<ItemID\>([0-9]+)\<\/ItemID\>/',
            'field' => [
                'item_id'
            ]
        ]
    ];

    public function __construct(DataStoresInterface $dataStore, $notificationType = null)
    {
        $this->dataStore = $dataStore;
        $this->notificationType = $notificationType;
    }

    /**
     * @param null $notificationType
     */
    public function setNotificationType($notificationType)
    {
        $this->notificationType = $notificationType;
    }

    public function getAll()
    {
        if ($this->notificationType === null) {
            throw new \Exception("Not set notification Type");
        }
        $query = new Query();
        $query->setQuery(new EqNode(AllNotificationDataStoreFactory::KEY_EBAY_NOTIFICATION_TYPE, $this->notificationType));

        return $this->dataByType($this->dataStore->query($query));
    }

    protected function dataByType($data)
    {
        $pattern = $this->regParserData[$this->notificationType]['regExp'];
        $field = $this->regParserData[$this->notificationType]['field'];
        foreach ($data as &$item) {
            $result = [];
            if (preg_match($pattern, $item['data'], $result)) {
                for ($i = 1; $i < count($result); $i++) {
                    $item[$field[$i-1]] = $result[$i];
                }
            }
        }
        return $data;
    }
}
