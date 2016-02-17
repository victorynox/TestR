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


class AuthorizationMiddleware
{

    public function __invoke(Request $request, Response $response, callable $next)
    {

        return $next($request, $response);
    }

}