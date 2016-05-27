<?php
/**
 * Created by PhpStorm.
 * User: victorynox
 * Date: 11.02.16
 * Time: 10:45
 */

namespace victorynox\Util;

class Math
{

    /**
     * divide a / b;
     * @param $a float divided
     * @param $b float divider
     * @return float the result of the division
     * @throws \Exception
     */
    public function divide($a, $b)
    {

        if ((isset($b) && isset($a)) && ((is_float($b) || is_int($b) && ((is_float($a) || is_int($a)))) && $b != 0)) {
            return $a/$b;
        } else {
            throw new \Exception("error by divide");
        }
        /*if(){

        }*/

    }
}
