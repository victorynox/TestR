<?php
/**
 * Created by PhpStorm.
 * User: victorynox
 * Date: 15.02.16
 * Time: 15:16
 */

namespace victorynox\View\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Class ViewMiddleware
 * @package App\Middleware
 * @TODO create format check returned data (html|json|xml?)
 */
class ViewMiddleware
{
    public function __invoke(Request $request, Response $response, callable $next)
    {
        //$query = $request->getAttribute('View');

        $query = $request->getParsedBody();
        
        if (isset($query['view'])) {
            $view = $query['view'];
            if (isset($view['render'])) {
                if (isset($view['code'])) {
                    $response->getBody()->write($view['render']);
                    return $response->withStatus($view['code']);
                }
            }
        }

        return $next($request, $response);
    }
}
