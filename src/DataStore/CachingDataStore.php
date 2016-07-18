<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 10.07.16
 * Time: 11:29
 */

namespace victorynox\DataStore;

use Xiag\Rql\Parser\Node\Query\ScalarOperator\EqNode;
use Xiag\Rql\Parser\Query;
use zaboy\rest\DataStore\DataStoreException;
use zaboy\rest\DataStore\Interfaces\DataStoresInterface;
use zaboy\rest\DataStore\Memory;

class CachingDataStore extends Memory
{

    /** @var  DataStoresInterface */
    protected $cachingDbTable;

    protected $dataIdentifierString = 'data_id';

    protected $cdsIdentifierString = 'cds_id';

    //30
    protected $fieldType = [
        //9
        'int' => [
            'id',
            'category_price',
            'count_sold',
            'count_push',
            'count_publish',
            'count_model_sold',
            'count_model_publish',
            'count_view',
            'Hour',
        ],
        //14
        'float' => [
            'x',
            'y',
            'mean_price',
            'price',
            'prob',
            'new_prob',
            'prof_mounth',
            'new_prof_mounth',
            'delta_prof_mounth',
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday',
            'Sunday',
        ],
        //7
        'string' => [
            'title',
            'seller_name',
            'vehicle',
            'vehicles',
            'ProductID',
            'vehicle_id',
            'ebaycategory_id',
        ]
    ];

    public function __construct(DataStoresInterface $cachingDbTable, $cdsId)
    {
        $this->cachingDbTable = $cachingDbTable;

        $query = new Query();
        $query->setQuery(new EqNode($this->cdsIdentifierString, $cdsId));

        $this->items = $this->cachingDbTable->query($query);

        foreach ($this->items as &$item) {
            $item[$this->cachingDbTable->getIdentifier()] = $item[$this->dataIdentifierString];
            unset($item[$this->dataIdentifierString]);
            unset($item[$this->cdsIdentifierString]);

            $item = array_filter($item);

            foreach ($item as $key => $value) {
                switch ($key) {
                    case in_array($key, $this->fieldType['int']) : {
                        if (is_numeric($value)) {
                            $item[$key] = (int)$value;
                        } else {
                            throw new \Exception('Can\'t parse to int value:' . $value . 'from field: ' . $key);
                        }
                        break;
                    }
                    case in_array($key, $this->fieldType['float']) : {
                        if (is_numeric($value)) {
                            $item[$key] = (float)$value;
                        } else {
                            throw new \Exception('Can\'t parse to float value:' . $value . 'from field: ' . $key);
                        }
                        break;
                    }
                    case in_array($key, $this->fieldType['string']) : {
                        $item[$key] = (string)$value;
                        break;
                    }
                    default:
                        throw new \Exception('Type not set for field: ' . $key);
                }
            }

        }

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     *
     * {@inheritdoc}
     */
    public function create($itemData, $rewriteIfExist = false)
    {
        throw new DataStoreException('CachingDataStore don\'t support create item');
    }

    /**
     * {@inheritdoc}
     *
     * {@inheritdoc}
     */
    public function update($itemData, $createIfAbsent = false)
    {
        throw new DataStoreException('CachingDataStore don\'t support update item');
    }

    public function deleteAll()
    {
        throw new DataStoreException('CachingDataStore don\'t support deleteAll item');
    }

    /**
     * {@inheritdoc}
     *
     * {@inheritdoc}
     */
    public function delete($dataId)
    {
        throw new DataStoreException('CachingDataStore don\'t support delete item');
    }
}
