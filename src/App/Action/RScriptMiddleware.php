<?php
/**
 * Created by PhpStorm.
 * User: victorynox
 * Date: 10.02.16
 * Time: 16:46
 */

namespace App\Action;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class RScriptMiddleware
{
    public function __invoke(Request $request, Response $response, callable $next){
        $scriptName = $request->getAttribute('script_name') . '.R';
        $query = $request->getParsedBody();
        #$pathToRScript = __DIR__ . '/../../../public/r_script/';
        //$dbConfig = 'root 123qwe321 127.0.0.1 TEST_R ';
        $dbConfig = 'victorynox 192.168.1.108 192.168.1.108 TEST_R ';
        $scriptExec = 'Rscript '. $query['path']['scriptFolder'] . $scriptName. ' ' . $dbConfig;

        foreach($query['data'] as $item){
            $scriptExec .= $item . ' ';
        }
        $out = [];
        exec($scriptExec, $out);
        foreach($out as $item){
            $item = explode('"', $item);
            $item = $item[1];
            //$files['path'] = $query['path']['outPutFolder'] . $item;
            if($query['return'] === 'plot'){
                $response->getBody()->write("<img src='/". $query['path']['local'] . $item .".png' width='400' />");
            }else{
                $response->getBody()->write("<a href='/". $query['path']['local'] . $item. ".csv'>" . $item . "</>");
            }
        }
        /*
        $csvFileName = explode('"',  $out[1]);
        $csvFileName = $csvFileName[1] . '.csv';
        $response->getBody()->write($csvFileName);

        $File = fopen($query['path']['outPutFolder'] .$csvFileName,'r+');

        while(($csv = fgetcsv($csvFile)) !== FALSE){
            $response->getBody()->write($csv[0] . "\n");
        }
        */
        return $response->withHeader("Content-Type", 'text/html');

        //$response->getBody()->write($scriptExec . "\n");
        //return $response->withAddedHeader("Content-Type", 'text/html');

    }

}