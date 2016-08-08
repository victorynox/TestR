<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 28.03.16
 * Time: 15:18
 */

namespace victorynox\DataStore\Pipes\Factory;

use Interop\Container\ContainerInterface;
use victorynox\DataStore\TablePreferenceList\TablePreferenceListAspect;
use victorynox\DataStore\TablePreferenceList\TablePreferenceListMiddleware;
use zaboy\rest\DataStore\DataStoreException;
use zaboy\rest\Middleware\DataStoreRest;
use zaboy\rest\Middleware\Factory\DataStoreDirectFactory;
use zaboy\rest\Pipe\Factory\RestRqlFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use zaboy\rest\RqlParser\RqlParser;

class RestPipeFactory
{
    public function __invoke(ContainerInterface $container, $requestedName)
    {

        /*$lazyTPDSR = function (Request $request, Response $response, callable $next) use ($container) {

            $resourceName = $request->getAttribute('Resource-Name');
            $DataStoreDirectFactory = new DataStoreDirectFactory();

            $storeMiddleware = $DataStoreDirectFactory($container, $resourceName);

            if($storeMiddleware instanceof TablePreferenceListMiddleware){
                $rqlQuery = $request->getAttribute('Rql-Query-Object');
                $strQuery = RqlParser::rqlEncode($rqlQuery);

                // strlen('eq(type,)') === 9
                if ($strQuery !== null && strlen(trim($strQuery)) > 9) {
                    $matches = [];
                    if (preg_match('/eq\(tableName\,([\w_\-\.\,\*]+)\)/', $strQuery, $matches)) {
                        $tableName = $matches[1];
                        if (method_exists($storeMiddleware, 'setTableDS')) {

                            if (!$container->has($tableName)) {
                                throw new DataStoreException(
                                    'Can\'t get Store' . $tableName
                                    . ' for Middleware TablePreferenceDataStoreRest');
                            }

                            $tableDS = $container->get($tableName);

                            $storeMiddleware->setTableDS($tableDS);
                            $storeMiddleware->setTableName($tableName);
                        }

                    }
                }
            }

            return $storeMiddleware($request, $response, $next);
        };*/

        $pipeFactory =  new RestRqlFactory();
        return $pipeFactory($container, $requestedName, []);
    }
}