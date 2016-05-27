<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 30.03.16
 * Time: 11:18
 */

namespace victorynox\Ebay\Category\Action;

use Interop\Container\ContainerInterface;
use Zend\Expressive\Router\RouterInterface;

class GetEbayCategoryFactory
{

    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get("config");

        return new GetEbayCategoryAction($config['ebay']);
    }
}
