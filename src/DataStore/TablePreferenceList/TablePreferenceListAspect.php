<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 25.07.16
 * Time: 15:39
 */

namespace victorynox\DataStore\TablePreferenceList;

use Xiag\Rql\Parser\Query;
use zaboy\rest\DataStore\Aspect\AspectAbstract;
use zaboy\rest\DataStore\Interfaces\DataStoresInterface;
use zaboy\rest\RqlParser\RqlParser;

class TablePreferenceListAspect extends AspectAbstract
{
    /** @var  DataStoresInterface */
    protected $tableDS;

    /** @var  string */
    protected $tableName;

    /**
     * @param string $tableName
     */
    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    public function setTableDS(DataStoresInterface $tableDS)
    {
        $this->tableDS = $tableDS;
    }

    protected function postQuery(&$result, Query $query)
    {
        if ($query->getQuery()) {
            $queryStr = urldecode(RqlParser::rqlEncode($query));
            $pattern = '/^and\(eq\(tableName\,' . $this->tableName . '\)\,eq\(name\,_default\)\)/';
            if (preg_match($pattern, $queryStr)) {
                if (count($result) === 0) {
                    if (method_exists($this->tableDS, 'getTableConfig')) {
                        $preference = $this->tableConfigToPreference($this->tableDS->getTableConfig());

                        $res = [
                            'table_name' => $this->tableName,
                            'name' => '_default',
                            'preference' => json_encode($preference)
                        ];
                       foreach ($res as $key => $item){
                           $result[0][$key] = $item;
                       }
                    }
                }
            }
        }
    }

    protected function tableConfigToPreference($preference)
    {
        $data = [];
        foreach ($preference as $key => $item) {
            $data[] = [
                'label' => $key,
                'field' => $key
            ];
        }
        return $data;
    }
}
