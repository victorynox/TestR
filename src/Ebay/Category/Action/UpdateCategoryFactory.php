<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 01.04.16
 * Time: 18:03
 */

namespace App\Action;

use Interop\Container\ContainerInterface;
use victorynox\DataStore\Cashable\CashableStores\CashableStoreAbstract;
use victorynox\Ebay\Category\Action\UpdateCategoryAction;

class UpdateCategoryFactory
{
    public function __invoke(ContainerInterface $container)
    {
        // TODO: Implement __invoke() method.
        if ($container->has('update-category')) {
            $cashableStore = $container->get('update-category');
            if ($cashableStore instanceof CashableStoreAbstract) {
                return new UpdateCategoryAction($cashableStore);
            } else {
                throw new \Exception("update-category don't extend CashableStoreAbstract");
            }
        } else {
            throw new \Exception("Cant find update-category");
        }
    }
}