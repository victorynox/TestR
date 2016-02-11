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
use App\Util\Math;
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

        $math = new Math();
        $query = $request->getQueryParams();

        $results =$math->divide((float)$query['a'], (float)$query['b']);
        //return $response->withHeader("Content-Type", 'text/html');
        return new HtmlResponse($this->template->render('app::hello', ['result' => $results]), 200);

    }

}