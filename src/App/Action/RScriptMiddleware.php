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
use Zend\Http\Client;
use Zend\Http\Client\Adapter\Curl;
use Zend\Http\Header\Connection;

class RScriptMiddleware
{
    public function __invoke(Request $request, Response $response, callable $next){
        $scriptName = $request->getAttribute('script_name') . '.R';
        $query = $request->getParsedBody();
        #$pathToRScript = __DIR__ . '/../../../public/r_script/';
        //$dbConfig = 'root 123qwe321 127.0.0.1 TEST_R';

        //TODO переделать вызов R скрипта
        //TODO вывод даных в таблицу
        //TODO вывод графиков
        $client = new Client('http://localhost:9997/');

        $client->setOptions(array(
            'adapter' => 'Zend\Http\Client\Adapter\Curl',
            'timeout' => 30
        ));

        $client->setMethod("POST");
        $client->setParameterPost($query['data']);
        $client->setHeaders(array(Connection::fromString("Connection: 'keep-alive'")));

        $resp = $client->send();

        if($resp->getStatusCode() == '200'){
            if($resp->getBody()){
                $response->getBody()->write($resp->getBody());
            }
        }


        /*
        try{
            $out = [];
            exec($scriptExec, $out);
            if(count($out) == 1){
                $out =  explode('"', $out);
                $out = $out[1];
                if($out === 'error'){
                    throw new \Exception("Data not found", 404);
                }
            }else{
                foreach ($out as $item) {
                    $item = explode('"', $item);
                    $item = $item[1];
                    //$files['path'] = $query['path']['outPutFolder'] . $item;
                    if ($query['return'] === 'plot') {
                        $response->getBody()->write("<img src='/" . $query['path']['local'] . $item . ".png' width='960' />");
                    } else {
                        $response->getBody()->write("<a href='/" . $query['path']['local'] . $item . ".csv'>" . $item . "</>");
                    }
                }
            }
        }catch(\Exception $ex){

        }
        */


        /*
        $csvFileName = explode('"',  $out[1]);
        $csvFileName = $csvFileName[1] . '.csv';
        $response->getBody()->write($csvFileName);

        $File = fopen($query['path']['outPutFolder'] .$csvFileName,'r+');

        while(($csv = fgetcsv($csvFile)) !== FALSE){
            $response->getBody()->write($csv[0] . "\n");
        }
        */
        return $response->withHeader("Content-Type", 'text/json');

        //$response->getBody()->write($scriptExec . "\n");
        //return $response->withAddedHeader("Content-Type", 'text/html');

    }

}