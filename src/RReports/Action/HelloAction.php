<?php
/**
 * Created by PhpStorm.
 * User: victorynox
 * Date: 10.02.16
 * Time: 11:44
 */

namespace victorynox\RReports\Action;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Zend\Expressive\Template\TemplateRendererInterface;

class HelloAction
{
    private $template;
    private $rscriptConfig;

    public function __construct(TemplateRendererInterface $template, $config)
    {
        $this->template = $template;
        $this->rscriptConfig = $config['rscript_config'];

    }

    public function __invoke(Request $request, Response $response, callable $next)
    {

        $render = $this->template->render('app::homepage');
        $query['view']['render'] = $render;
        $query['view']['code'] = 200;
        $request = $request->withParsedBody($query);
        return $next($request, $response);

    }
}
