<?php
/**
 * Created by PhpStorm.
 * User: victorynox
 * Date: 15.02.16
 * Time: 13:53
 */

namespace victorynox\Auth\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Authentication\AuthenticationService;

class IdentificationMiddleware
{

    public function __invoke(Request $request, Response $response, callable $next)
    {

        $auth = new AuthenticationService();
        $query = $request->getParsedBody();
        if ($auth->hasIdentity()) {
            $identity = $auth->getIdentity();
            $query['role'] = $identity['role'];
        } else {
            $query['role'] = 'guest';
        }
        $request = $request->withParsedBody($query);
        return $next($request, $response);
    }
}
