<?php
/**
 * Created by PhpStorm.
 * User: victorynox
 * Date: 10.02.16
 * Time: 11:44
 */

namespace App\Action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Expressive\ZendView\ZendViewRenderer;
use Zend\Stratigility\MiddlewarePipe;


class HelloAction
{

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        // TODO: Implement __invoke() method.

        $query = $request->getQueryParams();
        $target = isset($query['target']) ? $query['target'] : 'World';

        $file = fopen(__DIR__ . '/../../../public/csv/views.csv', 'r+');

        while(($csv = fgetcsv($file)) !== FALSE){
            $response->getBody()->write($csv[0]);
        }


        //return $response->withHeader("Content-Type", 'text/csv');


    }



}