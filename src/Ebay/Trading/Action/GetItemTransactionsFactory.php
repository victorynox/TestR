<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14.07.16
 * Time: 12:12
 */

namespace victorynox\Ebay\Trading\Action;

use Interop\Container\ContainerInterface;

class GetItemTransactionsFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');
        $credentials = $config['ebay']['credentials'];
        $eBayAuthToken = $config['ebay']['eBayAuthToken'];

        return new GetItemTransactionsAction($credentials, $eBayAuthToken);
    }
}
