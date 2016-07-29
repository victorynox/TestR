<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 27.07.16
 * Time: 11:01
 */

namespace victorynox\DataStore\TablePreferenceList;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use zaboy\rest\DataStore\DataStoreException;
use zaboy\rest\Middleware\DataStoreRest;
use zaboy\rest\Middleware\Factory\DataStoreAbstractFactory;
use zaboy\rest\RqlParser\RqlParser;

//TODO не рабоботает фабрика для таблици с натройками для grid

class TablePreferenceDataStoreRestFactory extends DataStoreAbstractFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');
        $serviceConfig = $config['middleware'][$requestedName];
        //take store for Middleware

        $dataStoreServiceName = isset($serviceConfig['dataStore']) ? $serviceConfig['dataStore'] : null;

        if (!$container->has($dataStoreServiceName)) {
            throw new DataStoreException(
                'Can\'t get Store' . $dataStoreServiceName
                . ' for Middleware ' . $requestedName);
        }

        $dataStore = $container->get($dataStoreServiceName);

        $lazyTPDSR = function (Request $request, Response $response, $next) use ($container, $dataStore) {
            $rqlQuery = $request->getAttribute('Rql-Query-Object');
            $strQuery = RqlParser::rqlEncode($rqlQuery);

            // strlen('eq(type,)') === 9
            if ($strQuery !== null && strlen(trim($strQuery)) > 9) {
                $tableName = preg_grep('/eq\(tableName\,([\w_\-\.\,\*]+)\)/g', $strQuery);
                if (count($tableName) > 1) {
                    $tableName = $tableName[1];
                    if (method_exists($dataStore, 'setTableDS')) {

                        if (!$container->has($tableName)) {
                            throw new DataStoreException(
                                 'Can\'t get Store' . $tableName
                                . ' for Middleware TablePreferenceDataStoreRest');
                        }

                        $tableDS = $container->get($tableName);

                        $dataStore->setTableDS($tableDS);
                    }

                }
            }

            $dataStoreRest = new DataStoreRest($dataStore);

            return $dataStoreRest($request, $response, $next);
        };

        return $lazyTPDSR;
    }
}


// rest/tablePreference?and(eq(tableName,table),eq(name,_default))