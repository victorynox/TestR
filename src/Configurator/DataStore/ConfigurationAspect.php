<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 05.08.16
 * Time: 13:30
 */

namespace victorynox\Configurator\DataStore;

use victorynox\Configurator\Interfaces\ConfigureGeneratorFactoryInterface;
use Xiag\Rql\Parser\Query;
use zaboy\rest\DataStore\Aspect\AspectAbstract;
use zaboy\rest\DataStore\Interfaces\DataStoresInterface;
use zaboy\rest\RqlParser\RqlParser;

class ConfigurationAspect extends AspectAbstract
{
    /** @var  ConfigureGeneratorFactoryInterface */
    protected $configureGeneratorFactory;

    public function __construct(DataStoresInterface $dataStore, ConfigureGeneratorFactoryInterface $configureGeneratorFactory)
    {
        $this->configureGeneratorFactory = $configureGeneratorFactory;
        parent::__construct($dataStore);
    }


    protected function postQuery(&$result, Query $query)
    {
        if ($query->getQuery()) {
            if (count($result) === 0) {
            $queryStr = urldecode(RqlParser::rqlEncode($query));
            $pattern = '/^and\(eq\(tableName\,([a-zA-Z0-9_]+)\)\,eq\(name\,_default\)\)/';
            $match = [];
                if (preg_match($pattern, $queryStr, $match)) {
                    $requestName = isset($match[1]) ? $match[1] : "";
                    $configuration = $this->configureGeneratorFactory->__invoke($requestName)->getConfiguration();

                    $res = [
                        'table_name' => $requestName,
                        'name' => '_default',
                        'preference' => json_encode($configuration)
                    ];

                    foreach ($res as $key => $item){
                        $result[0][$key] = $item;
                    }
                }
            }
        }
    }

}
