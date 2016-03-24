<?php
/**
 * Created by PhpStorm.
 * User: victorynox
 * Date: 15.02.16
 * Time: 13:06
 */

namespace Auth;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 *
 *
 */
class AuthorizationMiddleware
{

    public function __invoke(Request $request, Response $response, callable $next)
    {
        $query = $request->getParsedBody();
        $uri = $request->getUri();

        if($uri->getPath() != "/auth"){
            if('admin' != $query['role']){
                throw new \Exception("You are not authorized", 403);
            }
        }

        return $next($request, $response);
    }

}