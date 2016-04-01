<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 31.03.16
 * Time: 17:22
 */

namespace App\DataStore\Cashable\CashableStore;


use App\DataStore\Cashable\CashableStores\GetAllInterface;
use zaboy\res\DataStore\Memory;

class MemoryGetAll extends Memory implements GetAllInterface
{
    public function getAll()
    {
        return $this->getIterator();
    }
    
}