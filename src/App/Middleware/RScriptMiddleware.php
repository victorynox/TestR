<?php
/**
 * Created by PhpStorm.
 * User: victorynox
 * Date: 10.02.16
 * Time: 16:46
 */

namespace App\Middleware;

use Zend\Stratigility\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class RScriptMiddleware
{
    public function __invoke(Request $request, Response $response, callable $next){
        $scriptName = $request->getAttribute('script_name') . '.R';
        $query = $request->getQueryParams();
        $pathToRScript = __DIR__ . '/../../../public/r_script/';
        $dbConfig = 'root 123qew321 127.0.0.1 TEST_R ';
        $scriptExec = 'Rscript '. $pathToRScript . $scriptName. ' ' . $dbConfig;

        foreach($query as $item){
            $scriptExec .= $item . ' ';
        }

        $response->getBody()->write($scriptExec);
        return $response->withHeader("Content-Type", 'text/html');

    }

}