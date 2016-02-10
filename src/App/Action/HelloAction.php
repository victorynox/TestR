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


class HelloAction
{

    /*private $renderer;

    public function __construct(ZendViewRenderer $renderer)
    {
        $this->renderer = $renderer;
    }*/

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        // TODO: Implement __invoke() method.
        $query = $request->getQueryParams();
        $target = isset($query['target']) ? $query['target'] : 'World';


        /*return new HtmlResponse(
            $this->renderer->render('app/hello', ['target' => $target]
            ));*/
        

        $response->getBody()->write("csv or img");
        return $response->withHeader("Content-Type", 'text/html');
    }


}