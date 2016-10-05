<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 26.07.16
 * Time: 14:30
 */

namespace victorynox\Notification\DataStore\NotificationType;

use zaboy\rest\DataStore\Memory;

class TypeNotificationDataStore extends Memory
{
    protected static $tableConfig = [
        'id' => [
            'field_type' => 'Integer',
            'field_params' => [
                'nullable' => false
            ]
        ],
        'type' => [
            'field_type' => 'Varchar',
            'field_params' => [
                'length' => 255,
                'nullable' => false
            ]
        ],
        'count' => [
            'field_type' => 'Integer',
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
