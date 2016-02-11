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
     * @var Math
     */
    private $object;

    public function setUp()
    {
        $this->object = new Math();
        parent::setUp();
    }

    /**
     * @param $a
     * @param $b
     * @param $c
     * @dataProvider provider
     */
    public function testDivide($a, $b, $c){

        $this->assertEquals($c, $this->object->divide($a, $b));

    }

    public function provider(){
        return[
            [2 ,2 ,1],
            [9 ,3 ,3],
            [1 ,2 ,0.5],
        ];
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