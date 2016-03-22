<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 10.03.16
 * Time: 16:19
 */

namespace App\Middleware;

use Interop\Container\ContainerInterface;
use zaboy\rest\Middleware\ResourceResolver;
use zaboy\rest\Middleware\ResponseEncoder;
use zaboy\rest\Middleware\RqlParser;
use zaboy\rest\Middlewares\Factory\StoreMiddlewareFactory;
use zaboy\rest\Pipe\RestPipe;
use zaboy\rest\Pipes\Factory\RestPipeFactory;

class RRestPipeFactory extends RestPipeFactory
{
    public function __invoke(ContainerInterface $container, $requestedName)
    {
        $storeMiddlewareLazy = function (
            $request,
            $response,
            $next = null
        ) use ($container) {
            $resourceName = $request->getAttribute('Resource-Name');
            $storeMiddlewareFactory = new StoreMiddlewareFactory();
            $storeMiddleware = $storeMiddlewareFactory($container, $resourceName);
            return $storeMiddleware($request, $response, $next);
        };


        $this->middlewares[100] = new ResourceResolver();
        $this->middlewares[200] = new RequestDecoder();
        $this->middlewares[300] = new RqlParser();
        $this->middlewares[400] = $storeMiddlewareLazy;
        $this->middlewares[500] = new ResponseEncoder();
        //$middlewares[600] = new Middleware\$errorHandler();

        ksort($this->middlewares);
        return new RestPipe($this->middlewares);
    }
}