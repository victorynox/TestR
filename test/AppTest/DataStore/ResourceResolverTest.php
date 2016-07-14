<?php

/**
 * Created by PhpStorm.
 * User: root
 * Date: 31.03.16
 * Time: 13:58
 */
namespace victorynox\AppTest\DataStore;

use zaboy\rest\Middleware\ResourceResolver;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;

class ResourceResolverTest extends PHPUnit_Framework_TestCase
{
    /** @var  \zaboy\rest\Middleware\ResourceResolver */
    private $object;

    /** @var  Response */
    private $response;

    /** @var  ServerRequest */
    private $request;

    /** @var  callable */
    private $next;

    public function setUp()
    {
        $this->object = new ResourceResolver();
        $this->response = new Response();
        parent::setUp(); // TODO: Change the autogenerated stub
    }

    /**
     * @param $query
     * @param $resourceName
     * @param $primaryKey
     * @dataProvider testResourceResolverDataProvider
     */
    public function testResourceResolver__invokeWithoutRouting($query, $resourceName, $primaryKey){
        $this->request = new ServerRequest([], [], $query);
        $this->next = function($req, $resp){
            return $req;
        };

        $responseResolver = $this->object->__invoke($this->request, $this->response, $this->next);
        $this->assertEquals($responseResolver->getAttribute('Resource-Name'), $resourceName);
        $this->assertEquals($responseResolver->getAttribute('Primary-Key-Value'), $primaryKey);
    }

    public function testResourceResolverDataProvider(){
        return [
            ["/category", "category", null],
            ["/cate-gory", "cate-gory", null],
            ["/cate_gory", "cate_gory", null],
            ["/category/", "category", null],
            ["/1e-E_s/", "1e-E_s", null],
            ["/1e-E_s?a=2", "1e-E_s", null],
            ["/1e-E_s/?a=2", "1e-E_s", null],
            ["/1e-E_s2/q?DS=2", "1e-E_s2", "q"],
            ["/1e-E_s2/1w-dx_s?DS=2", "1e-E_s2", "1w-dx_s"],
            ["/1e-E_s2/1w-dx_s/?DS=2", "1e-E_s2", "1w-dx_s"],
            ["/1e-E_s2/1w-dx_s/?DS=2&q=2", "1e-E_s2", "1w-dx_s"],
        ];
    }


    /**
     * @param $resourceName
     * @param $primaryKey
     * @dataProvider testResourceResolverWhirRoutDataProvider
     */
    public function testResourceResolver__invokeWithRouting($resourceName, $primaryKey){
        $this->request = new ServerRequest([], []);

        $this->request = $this->request->withAttribute("resourceName", $resourceName);
        $this->request = $this->request->withAttribute("id", $primaryKey);

        $this->next = function($req, $resp){
            return $req;
        };

        $responseResolver = $this->object->__invoke($this->request, $this->response, $this->next);
        $this->assertEquals($responseResolver->getAttribute('Resource-Name'), $resourceName);
        $this->assertEquals($responseResolver->getAttribute('Primary-Key-Value'), $primaryKey);
    }

    public function testResourceResolverWhirRoutDataProvider(){
        return [
            ["category", null],
            ["cate-gory", null],
            ["cate_gory", null],
            ["1e-E_s", null],
            ["1e-E_s2", "q"],
            ["1e-E_s2", "1w-dx_s"],
        ];
    }
}
