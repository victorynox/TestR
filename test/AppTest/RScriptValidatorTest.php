<?php
/**
 * Created by PhpStorm.
 * User: victorynox
 * Date: 11.02.16
 * Time: 11:31
 */

namespace AppTest;

use PHPUnit_Framework_TestCase;
use Zend\Diactoros\Response;
use Zend\Expressive\Application;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\ServerRequest;

class HelloActionTest extends  PHPUnit_Framework_TestCase
{
    /**
     * @param $host
     * @param $path_info
     * @param $probKey
     * @param $probeValue
     * @param $countKey
     * @param $countValue
     * @param $bool
     * @internal param $url
     * @dataProvider provider
     */
    public function testIndexAction($host, $path_info, $probKey, $probeValue ,$countKey, $countValue, $bool= false){

        /** @var \Interop\Container\ContainerInterface $container */
        $container = require 'config/container.php';

        $queryParams[$probKey] = $probeValue;
        $queryParams[$countKey] = $countValue;

        $request = (new ServerRequest(['SCRIPT_NAME' => 'index.php'], [], $host.$path_info, 'GET', 'php://input', [], [] ,$queryParams));
        $queryParams = $request->getQueryParams();
        echo $request->getUri() .'?'. $probKey . '=' . $queryParams[$probKey] . '&' . $countKey . '=' . $queryParams[$countKey] . "\n";

        /** @var Application $app */
        $app = $container->get('Zend\Expressive\Application');

        /** @var ResponseInterface $response */
        $response = $app($request, new Response());
        if($bool){
            $this->assertEquals(200, $response->getStatusCode());
            $this->assertTrue($response->hasHeader('Content-Type'));
            $this->assertEquals('text/html', $response->getHeader('Content-Type')[0]);

        }else{
            $this->assertEquals(500, $response->getStatusCode());
        }

    }

    public function provider(){
        return [
            ['http://localhost:8080', '/api/tableProduct', 'prob', 1.23, 'count', 50, true],
            ['http://localhost:8080', '/api/qwe', 'prob', 1.23, 'count', 50],
            ['http://localhost:8080', '/api/tableProduct', 'q', 1.23, 'count', 50],
            ['http://localhost:8080', '/api/tableProduct', 'prob', 'qwe', 'count', 50],
            ['http://localhost:8080', '/api/tableProduct', 'prob', 1.23, 'w', 50],
            ['http://localhost:8080', '/api/tableProduct', 'prob', 1.23, 'count', 'asd'],
        ];
    }

}