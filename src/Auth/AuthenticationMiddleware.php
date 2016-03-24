<?php
/**
 * Created by PhpStorm.
 * User: victorynox
 * Date: 12.02.16
 * Time: 12:29
 */

namespace Auth;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Authentication\AuthenticationService;
use Auth\Adapter\AuthAdapter;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template\TemplateRendererInterface;


class AuthenticationMiddleware
{

    private $config;
    private $template;


    public function __construct(TemplateRendererInterface $renderer, $config)
    {
        $this->config = $config;
        $this->template = $renderer;

    }

    /**
     * @param Request $request
     * @param Response $response
     * @param callable $next
     * @return \Psr\Http\Message\MessageInterface|HtmlResponse
     * @throws Exception
     */
    public function __invoke(Request $request, Response $response, callable $next)
    {

        //$form = new LoginForm('Login', []);
        //$form->get('submit')->setValue('Login');
        if ($request->getMethod() == 'POST') {
            $auth = new AuthenticationService();
            $query = $request->getParsedBody();
            $authAdapter = new AuthAdapter($query['login'], $query['password'], $this->config);
            $result = $auth->authenticate($authAdapter);
            if (!$result->isValid()) {
                //$response->getBody()->write("Not valid authentication\n");
                //return $response->withStatus(403)->withHeader("Content-type", 'text/html');
                throw new Exception("Not valid authentication\n", 403);
            } else {

                if ($request->getUri()->getPath() === '/auth') {
                    $render = $this->template->render('app::homepage');
                    $query = $request->getParsedBody();
                    $query['view']['render'] = $render;
                    $query['view']['code'] = 200;
                    $request = $request->withParsedBody($query);
                }
                return $next($request, $response);
            }
        } else {
            $render = $this->template->render('app::login', ['error' => null]);

            $query = $request->getParsedBody();
            $query['view']['render'] = $render;
            $query['view']['code'] = 200;

            $request = $request->withParsedBody($query);

            return $next($request, $response);

        }


    }

}