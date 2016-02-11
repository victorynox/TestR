<?php
/**
 * Created by PhpStorm.
 * User: victorynox
 * Date: 11.02.16
 * Time: 10:45
 */

namespace App\Util;


class Math
{

    /**
     * divide a / b;
     * @param $a float
     * @param $b float
     * @return float
     * @throws \Exception
     */
    public function divide($a, $b){
        if(!(boolean)$b){
            throw new \Exception("Division by zero");
        }
        return $a/$b;
    }


}