<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 07.07.16
 * Time: 17:36
 */

namespace victorynox\DataStore;

use Xiag\Rql\Parser\Node\Query\LogicOperator\AndNode;
use Xiag\Rql\Parser\Node\Query\ScalarOperator\EqNode;
use Xiag\Rql\Parser\Query;
use zaboy\rest\DataStore\Aspect\AspectAbstract;
use zaboy\rest\DataStore\DataStoreException;
use zaboy\rest\DataStore\HttpClient;
use zaboy\rest\DataStore\Interfaces\DataStoresInterface;

class GetBrandAspect extends AspectAbstract
{

    protected $scriptName;

    public function __construct(DataStoresInterface $dataStore)
    {
        $this->scriptName = "getBrand";
        parent::__construct($dataStore);
    }

    protected function preQuery(Query &$query)
    {
        $scriptNameNode = new EqNode('scriptName', $this->scriptName);

        if ($query->getQuery()) {
            $query->setQuery(new AndNode([$scriptNameNode, $query->getQuery()]));
        } else {
            $query->setQuery($scriptNameNode);
        }
    }

    protected function postQuery(&$result, Query $query)
    {
        foreach ($result as &$data){
            if($data['id'] == 'All Brand'){
                $data['selected'] = true;
            }
        }
    }
    
}
