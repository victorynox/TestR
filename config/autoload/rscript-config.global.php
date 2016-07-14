<?php
/**
 * Created by PhpStorm.
 * User: victorynox
 * Date: 11.02.16
 * Time: 14:19
 * {script_name} => { {script params}, {script return}}
 */
return [
    'rscript_config' => [
        'path' =>[
            'tables' => __DIR__ . '/../../public/csv/',
            'plot' => __DIR__ . '/../../public/img/',
            'script' => __DIR__ . '/../../public/r_script/',
            'configForScript' => __DIR__ . '/local.configRScript.csv',
            'local' => [
                'tables' => 'csv/',
                'plot' => 'img/',
            ],
        ],
        'scripts' => array(
            'tableProduct' => [
                'get' => [
                    'prof' => [
                        'type' => 'float',
                        'required' => false,
                    ],
                    'count' => [
                        'type' => 'int',
                        'required' => false,
                    ]
                ],
                'return' => [
                    'plot'
                ],
            ],
            'plotPrice' => [
                'get' => [
                    'prof' => [
                        'label' => 'Процентаня ставка',
                        'formType' => 'TextBox',
                        'type' => 'float',
                        'required' => true,
                    ],
                    'beginData' => [
                        'label' => 'Дата начала выборки',
                        'format' => 'Y-m-d TH:iP',
                        'formType' => 'DateTextBox',
                        'type' => 'string',
                        'required' => true,
                    ],
                    'endData' => [
                        'label' => 'Дата окончания выборки',
                        'formType' => 'DateTextBox',
                        'type' => 'string',
                        'required' => true,
                    ],
                    'numberOfCategory' => [
                        'label' => 'Номер категории',
                        'formType' => 'TextBox',
                        'type' => 'int',
                        'required' => false,
                    ],
                    'brand' => [
                        'label' => 'Бренд',
                        'formType' => 'TextBox',
                        'type' => 'string',
                        'required' => false,
                    ],
                    /*
                    'maxPrice' => [
                        'type' => 'float',
                        'required' => false,
                    ],*/
                ],
                'return' => [
                    'plot'
                ],
            ],
            'plotPublishPrice' => [
                'get' => [
                    'brand' => [
                        'label' => 'Бренд',
                        'formType' => 'TextBox',
                        'type' => 'string',
                        'required' => false,
                    ],
                    'numberOfCategory' => [
                        'label' => 'Номер категории',
                        'formType' => 'TextBox',
                        'type' => 'int',
                        'required' => false,
                    ],
                    'beginDate' => [
                        'label' => 'Дата начала выборки',
                        'format' => 'Y-m-d TH:iP',
                        'formType' => 'DateTextBox',
                        'type' => 'string',
                        'required' => true,
                    ],
                    'endDate' => [
                        'label' => 'Дата окончания выборки',
                        'format' => 'Y-m-d TH:iP',
                        'formType' => 'DateTextBox',
                        'type' => 'string',
                        'required' => true,
                    ],
                ],
                'return' => [
                    'plot'
                ],
            ],
        ),


    ],


];
