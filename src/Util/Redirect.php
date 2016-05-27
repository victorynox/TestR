<?php
/**
 * Created by PhpStorm.
 * User: victorynox
 * Date: 15.02.16
 * Time: 11:19
 */

namespace victorynox\Util;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\UriInterface;

class Redirect
{

    public function __invoke(Request $request, Response $response, callable $next)
    {
        $url = $request->getUri();
        $path = $url->getPath();
        $query = $request->getQueryParams();
        //$redirect = $query['redirect'];

        /*
        if('/auth/logout' === $path){
            return $this->redirect('/', $url, $response);
        }*/

        return $next($request, $response);
    }

    /**
     * @param $path
     * @param $url UriInterface
     * @param $response Response
     * @param array $query
     * @return \Psr\Http\Message\MessageInterface
     */
    private function redirect($path, $url, $response, $query = [])
    {
        $url = $url->withPath($path);

        if (count($query)) {
            $url = $url->withQuery(http_build_query($query));
        }

        return $response->withStatus(301)->withHeader('Location', (string)$url);
    }
}
