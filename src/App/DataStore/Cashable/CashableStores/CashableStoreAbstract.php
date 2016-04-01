<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 31.03.16
 * Time: 17:00
 */

namespace App\DataStore\Cashable\CashableStores;


use Traversable;
use zaboy\res\DataStore\Memory;
use zaboy\res\DataStores\DataStoresInterface;

abstract class CashableStoreAbstract implements RefreshableInterface, DataStoresInterface
{

    /** @var  DataStoresInterface */
    private $cashStore;

    /** @var  GetAllInterface */
    private $getAll;

    public function __construct(GetAllInterface $getAll, DataStoresInterface $cashStore = null)
    {
        $this->getAll = $getAll;
        if (isset($cashStore)) {
            $this->cashStore = $cashStore;
        } else {
            $this->cashStore = new Memory();
        }
        $this->refresh();
    }

    /**
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        return $this->cashStore->getIterator();

    }

    /**
     * Return primary key
     *
     * Return "id" by default
     *
     * @see DEF_ID
     * @return string "id" by default
     */
    public function getIdentifier()
    {
        return $this->cashStore->getIdentifier();

    }

    /**
     * Return Item by id
     *
     * Method return null if item with that id is absent.
     * Format of Item - Array("id"=>123, "fild1"=value1, ...)
     *
     * @param int|string $id PrimaryKey
     * @return array|null
     */
    public function read($id)
    {

        return $this->cashStore->read($id);
    }

    /**
     * Return true if item with that id is present.
     *
     * @param int|string $id PrimaryKey
     * @return bool
     */
    public function has($id)
    {

        return $this->cashStore->has($id);
    }

    /**
     *
     * @return array array of keys or empty array
     */
    public function getKeys()
    {
        return $this->cashStore->getKeys();

    }

    /**
     * Return items by criteria with mapping, sorting and paging
     *
     * Example:
     * <code>
     * find(
     *    array('fild2' => 2, 'fild5' => 'something'), // 'fild2' === 2 && 'fild5 === 'something'
     *    array(self::DEF_ID), // return only identifiers
     *    array(self::DEF_ID => self::DESC),  // Sorting in reverse order by 'id" fild
     *    10, // not more then 10 items
     *    5 // from 6th items in result set (offset of the first item is 0)
     * )
     * </code>
     *
     * ORDER
     * http://www.simplecoding.org/sortirovka-v-mysql-neskolko-redko-ispolzuemyx-vozmozhnostej.html
     * http://ru.php.net/manual/ru/function.usort.php
     *
     * @see ASC
     * @see DESC
     * @param Array|null $where
     * @param array|null $filds What filds will be included in result set. All by default
     * @param array|null $order
     * @param int|null $limit
     * @param int|null $offset
     * @return array    Empty array or array of arrays
     */
    public function find(
        $where = null,
        $filds = null,
        $order = null,
        $limit = null,
        $offset = null
    )
    {
        return $this->cashStore->find($where, $filds, $order, $limit, $offset);

    }

    public function refresh()
    {
        $this->cashStore->deleteAll();
        $all = $this->getAll->getAll();
        if (isset($all)) {
            if ($all instanceof Traversable or is_array($all)) {
                foreach ($all as $item) {
                    $this->cashStore->create($item);
                }
            } else {
                throw new \Exception("Not return refreshed all");
            }
        } else {
            throw new \Exception("Not return refreshed all");
        }

    }

    /**
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
        $this->cashStore->count();

    }

    /**
     * By default, insert new (by create) Item.
     *
     * It can't overwrite existing item by default.
     * You can get item "id" for creatad item us result this function.
     *
     * If  $item["id"] !== null, item set with that id.
     * If item with same id already exist - method will throw exception,
     * but if $rewriteIfExist = true item will be rewrited.<br>
     *
     * If $item["id"] is not set or $item["id"]===null,
     * item will be insert with autoincrement PrimryKey.<br>
     *
     * @param array $itemData associated array with or without PrimaryKey
     * @param bool $rewriteIfExist
     * @return int|null|string "id" for creatad item or null if item wasn't created
     * @throws \Exception
     */
    public function create($itemData, $rewriteIfExist = false)
    {
        if ($this->getAll instanceof DataStoresInterface) {
            $this->getAll->create($itemData, $rewriteIfExist);
        } else {
            throw new \Exception("Refreshable dont implement DataStoresInterface");
        }

    }

    /**
     * By default, update existing Item.
     *
     * If item with PrimaryKey == $item["id"] is existing in store, item will updete.
     * Filds wich don't present in $item will not change in item in store.<br>
     * Method will return updated item<br>
     * <br>
     * If $item["id"] isn't set - method will throw exception.<br>
     * <br>
     * If item with PrimaryKey == $item["id"] is absent - method  will throw exception,<br>
     * but if $createIfAbsent = true item will be created and method return inserted item<br>
     * <br>
     *
     * @param array $itemData associated array with PrimaryKey
     * @param bool $createIfAbsent
     * @return array updated item or inserted item
     * @throws \Exception
     */
    public function update($itemData, $createIfAbsent = false)
    {
        if ($this->getAll instanceof DataStoresInterface) {
            $this->getAll->update($itemData, $createIfAbsent);
        } else {
            throw new \Exception("Refreshable dont implement DataStoresInterface");
        }

    }

    /**
     * Delete Item by id. Method do nothing if item with that id is absent.
     *
     * @param int|string $id PrimaryKey
     * @return int number of deleted items: 0 , 1 or null if object doesn't support it
     * @throws \Exception
     */
    public function delete($id)
    {
        if ($this->getAll instanceof DataStoresInterface) {
            $this->getAll->delete($id);
        } else {
            throw new \Exception("Refreshable dont implement DataStoresInterface");
        }

    }

    /**
     * Delete all Items.
     * @return int number of deleted items or null if object doesn't support it
     * @throws \Exception
     */
    public function deleteAll()
    {
        if ($this->getAll instanceof DataStoresInterface) {
            $this->getAll->deleteAll();
        } else {
            throw new \Exception("Refreshable dont implement DataStoresInterface");
        }

    }
}


