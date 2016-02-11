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
        $pathToCSV = __DIR__ . '/../../../public/csv/';
        $dbConfig = 'root 123qwe321 127.0.0.1 TEST_R ';
        $scriptExec = 'Rscript '. $pathToRScript . $scriptName. ' ' . $dbConfig;

        foreach($query as $item){
            $scriptExec .= $item . ' ';
        }


        /*exec($scriptExec, $out);

        $csvFileName = explode('"',  $out[1]);
        $csvFileName = $csvFileName[1] . '.csv';
        $response->getBody()->write($csvFileName);


        $csvFile = fopen($pathToCSV .$csvFileName,'r+');

        while(($csv = fgetcsv($csvFile)) !== FALSE){
            $response->getBody()->write($csv[0] . "\n");
        }

        return $response->withHeader("Content-Type", 'text/csv');*/
        $response->getBody()->write($scriptExec . "\n");
        return $response->withHeader("Content-Type", 'text/html');

    }

}