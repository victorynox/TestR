<?php
/**
 * Created by PhpStorm.
 * User: victorynox
 * Date: 11.02.16
 * Time: 11:00
 */

namespace AppTest;

use App\Util\Math;
use PHPUnit_Framework_TestCase;
use SebastianBergmann\RecursionContext\Exception;


class MathTest extends PHPUnit_Framework_TestCase
{

    /**

     * @throws \Exception

     */
    public function testDivide(){

        $math =new Math();

        $this->assertEquals(1, $math->divide(2, 2));
        $this->assertEquals(3, $math->divide(9, 3));
        $this->assertEquals(0.5, $math->divide(1, 2));
    }


    /**
     * @expectedException Exception
     */

    public function testException(){

        $this->setExpectedException('Exception');
        $math =new Math();
        $math->divide(8,0);
    }


}