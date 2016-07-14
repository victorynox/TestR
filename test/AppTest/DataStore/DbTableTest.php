<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 13.07.16
 * Time: 12:00
 */

namespace victorynox\AppTest\DataStore;


use zaboy\rest\DataStore\DbTable;
use zaboy\rest\DataStore\Interfaces\DataStoresInterface;

class DbTableTest extends \PHPUnit_Framework_TestCase
{
    /** @var  DbTable */
    protected $object;

    protected $container;
    protected $config;

    protected $dbTableName;
    protected $adapter;


    public function setUp()
    {
        $this->container = include './config/container.php';
        $this->config = $this->container->get('config')['dataStore'];

        $this->dbTableName = $this->config['testDbTable']['tableName'];
        $this->adapter = $this->container->get('db');

        $this->object = $this->container->get('testDbTable');
    }

   /* public function testWriteData1()
    {
        $allTime = 0;

        foreach (range(1, 25000) as $i) {
            srand();
            $startTime = $this->getMicrotime();
            $this->object->create([
                'name' => uniqid($i+rand()+$i, true),
                'val' => rand()/3,
                'data' => sha1(uniqid(md5(uniqid(rand(), true) . uniqid(rand(), true)), true))
            ]);
            $endTime = $this->getMicrotime();
            $time = $endTime-$startTime;
            $allTime += $time;
            printf("Start: %f\nEnd: %f\nDifference: %f\n\n",$startTime, $endTime, $time);
        }
        printf("=================== AllTime: %f ===================\n\n", $allTime);
    }*/

    public function testWriteData()
    {
        $data = [];
        foreach (range(1, 25000) as $i) {
            $item =[
                'name' => uniqid($i + rand() + $i, true),
                'val' => rand() / 3,
                'data' => sha1(uniqid(md5(uniqid(rand(), true) . uniqid(rand(), true)), true))
            ];
            $data[] = $item;
        }

        printf("%f %s %s\n", $data[0]['name'], $data[0]['val'], $data[0]['data']);

        $allTime = 0;

        /*foreach ($data as $item) {
            srand();
            $startTime = $this->getMicrotime();
            $this->object->create($item);
            $endTime = $this->getMicrotime();
            $time = $endTime-$startTime;
            $allTime += $time;
            printf("Start: %f\nEnd: %f\nDifference: %f\n\n",$startTime, $endTime, $time);
        }*/
        printf("=================== AllTime :%f  ===================\n\n", $allTime);
    }

    private function getMicrotime(){
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }
}
