<?php


// Delegate static file requests back to the PHP built-in webserver
/*
if (php_sapi_name() === 'cli-server'
    && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))
) {
    return false;
}*/
/*

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

*/
///** @var \Interop\Container\ContainerInterface $container */
//$container = require 'config/container.php';

///** @var \Zend\Expressive\Application $app */
//$app = $container->get('Zend\Expressive\Application');
//$app->run();



use Zend\Stratigility\MiddlewarePipe;
use Zend\Stratigility\FinalHandler;
use Zend\Diactoros\Server;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

require __DIR__ . '/../vendor/autoload.php';

$app    = new MiddlewarePipe();
$server = Server::createServer($app, $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);

// Landing page
$app->pipe('/', function (Request $req, Response $res, $next) {
    if (! in_array($req->getUri()->getPath(), ['/', ''], true)) {
        //$file = fopen(__DIR__ . 'csv/views.csv', 'r+');

       // while(($csv = fgetcsv($file)) !== FALSE){
            $res->getBody()->write("hello");
       // }
        return $next($req, $res);
    }
    return $res->end('Hello world!');
});

// Another page
$app->pipe('/foo', function (Request $req, Response $res, $next) {
    throw new Exception();

    return $res->withHeader("Content-Type", 'text/html');
});

$app->pipe('/d', function($error, Request $req, Response $res, $next){
    $res->getBody()->write($error);
    return $res->withHeader("Content-Type", 'text/text');
});

$server->listen();