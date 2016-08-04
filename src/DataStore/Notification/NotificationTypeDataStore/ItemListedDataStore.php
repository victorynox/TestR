<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 26.07.16
 * Time: 14:30
 */

namespace victorynox\DataStore\Notification\NotificationTypeDataStore;

use zaboy\rest\DataStore\Memory;

class ItemListedDataStore extends Memory
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
