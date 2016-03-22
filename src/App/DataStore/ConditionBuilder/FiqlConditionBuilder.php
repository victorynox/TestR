<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11.03.16
 * Time: 12:14
 */

namespace App\DataStore\ConditionBuilder;


use zaboy\res\DataStores\ConditionBuilderAbstract;

class FiqlConditionBuilder extends  ConditionBuilderAbstract
{
    protected $literals = [
        'LogicOperator' => [
            'and' => ['before' => '' , 'between' => '&' , 'after' =>''],
        ],
        'ArrayOperator' => [

        ],
        'ScalarOperator' => [
            'eq' => ['before' => '' , 'between' => '=' , 'after' =>''],
            'ne' => ['before' => '' , 'between' => '!=' , 'after' =>''],
            'ge' => ['before' => '' , 'between' => '>=' , 'after' =>''],
            'gt' => ['before' => '' , 'between' => '>' , 'after' =>''],
            'le' => ['before' => '' , 'between' => '<=' , 'after' =>''],
            'lt' => ['before' => '' , 'between' => '<' , 'after' =>''],
        ]
    ];

    protected $emptyCondition= '';
}