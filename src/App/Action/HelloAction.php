<?php
/**
 * Created by PhpStorm.
 * User: victorynox
 * Date: 10.02.16
 * Time: 11:44
 */

namespace App\Action;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Zend\Diactoros\Stream;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Diactoros\Response\HtmlResponse;

class HelloAction
{
    private $template;

    public function __construct(TemplateRendererInterface $template)
    {
        $this->template = $template;
    }

    public function __invoke(Request $request, Response $response, callable $next)
    {
        $render = $this->template->render('app::hello', ['message' => null]);

        $query = $request->getParsedBody();
        $query['view']['render'] = $render;
        $query['view']['code'] = 200;

        $request = $request->withParsedBody($query);
        return $next($request, $response);
    }

}