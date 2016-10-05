<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 05.10.16
 * Time: 12:09
 */

namespace victorynox\Notification\DataStore\NotificationType;

use zaboy\rest\DataStore\Memory;

class AuctionCheckoutCompleteDataStore extends Memory
{
    protected static $tableConfig = [
        'id' => [
            'field_type' => 'Integer',
            'field_params' => [
                'nullable' => false
            ]
        ],
        'add_date' => [
            'field_type' => 'Datetime',
            'field_params' => [
                'nullable' => false
            ]
        ],
        'item_id' => [
            'field_type' => 'Varchar',
            'field_params' => [
                'length' => 255,
                'nullable' => false
            ]
        ],
        'data' => [
            'field_type' => 'Blob',
            'field_params' => [
                'nullable' => false
            ]
        ],
    ];

    public static function getTableConfig()
    {
        return self::$tableConfig;
    }

}
