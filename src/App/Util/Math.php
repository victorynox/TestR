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
     * @param $a float divided
     * @param $b float divider
     * @return float the result of the division
     * @throws \Exception
     */
    public function divide($a, $b){
        if((isset($b) and isset($a)) and ((is_float($b) or is_int($b) and ((is_float($a) or is_int($a)))) and $b != 0)){
            return $a/$b;
        }else{
            throw new \Exception("error by divide");
        }
        /*if(){

        }*/

    }


}