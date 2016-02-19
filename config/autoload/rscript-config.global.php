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
            'local' => [
                'tables' => 'csv/',
                'plot' => 'img/',
            ],
        ],
        'tableProduct' => [
            'get' => [
                'prob' => [
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
                    'type' => 'float',
                    'required' => true,
                ],
                'numberOfCategory' => [
                    'type' => 'int',
                    'required' => false,
                ],
                'brand' => [
                    'type' => 'string',
                    'required' => false,
                ],
                /*'minPrice' => [
                    'type' => 'float',
                    'required' => false,
                ],
                'maxPrice' => [
                    'type' => 'float',
                    'required' => false,
                ],*/
            ],
            'return' => [
                'plot'
            ],

        ],
    ],


];
