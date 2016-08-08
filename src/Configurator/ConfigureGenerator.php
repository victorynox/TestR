<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 05.08.16
 * Time: 12:55
 */

namespace victorynox\Configurator;

use victorynox\Configurator\Interfaces\ConfigurableInterface;
use Xiag\Rql\Parser\Node\LimitNode;
use Xiag\Rql\Parser\Query;
use zaboy\rest\DataStore\Interfaces\DataStoresInterface;

class ConfigureGenerator implements ConfigurableInterface
{
    /**
     * @var DataStoresInterface
     */
    protected $dataStore;

    public function __construct(DataStoresInterface $dataStore)
    {
        $this->dataStore = $dataStore;
    }

    public function getConfiguration(){
        if($this->dataStore instanceof ConfigurableInterface){
            return $this->dataStore->getConfiguration();
        }

        $query = new Query();
        $query->setLimit(new LimitNode(1));
        $data = $this->dataStore->query($query);

        $config = [];
        if(count($data) > 0 and isset($data[0])){
            foreach ($data[0] as $key => $value){
                $config[] = ['label' => $key, 'field' => $key];
            }
        }
        return $config;
    }
}
