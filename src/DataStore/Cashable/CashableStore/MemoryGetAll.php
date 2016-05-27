<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 31.03.16
 * Time: 17:22
 */

namespace victorynox\DataStore\Cashable\CashableStore;

use victorynox\DataStore\Cashable\CashableStores\GetAllInterface;
use victorynox\DataStore\Cashable\CashableStores\GetAllInterface;
use zaboy\res\DataStore\Memory;

class MemoryGetAll extends Memory implements GetAllInterface
{
    public function getAll()
    {
        return $this->getIterator();
    }
}
