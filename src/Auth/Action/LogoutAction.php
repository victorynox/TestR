<?php
/**
 * Created by PhpStorm.
 * User: victorynox
 * Date: 15.02.16
 * Time: 13:38
 */

namespace victorynox\Auth\Action;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Authentication\AuthenticationService;
use Zend\Expressive\Template\TemplateRendererInterface;

class LogoutAction
{

    private $template;

    public function __construct(TemplateRendererInterface $renderer)
    {
        $this->template = $renderer;
    }

    public function __invoke(Request $request, Response $response, callable $next)
    {
        $auth = new AuthenticationService();
        if ($auth->hasIdentity()) {
            $auth->clearIdentity();
        }
        /*
                $render = $this->template->render('app::login');

                $query = $request->getParsedBody();
                $query['view']['render'] = $render;
                $query['view']['code'] = 200;

                $request = $request->withParsedBody($query);*/

        return $next($request, $response);
    }
}
