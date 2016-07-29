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
            $queryStr = RqlParser::rqlEncode($query->getQuery());
            if (preg_match('/$and\(eq\(tableName\,' . $this->tableName . '\)\,eq\(name\,_default\)\)/', $queryStr)) {
                if (count($result) === 0) {
                    if (method_exists($this->tableDS, 'getTableConfig')) {
                        $preference = $this->tableConfigToPreference($this->tableDS->getTableConfig());
                        $this->dataStore->create([
                            'table_name' => $this->tableName,
                            'name' => '_default',
                            'preference' => json_encode($preference)
                        ]);

                        $defaultPreference = $this->dataStore->query($query);
                        foreach ($defaultPreference as $key => $value) {
                            $result[$key] = $value;
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
