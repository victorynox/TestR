<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 30.03.16
 * Time: 15:43
 */

namespace victorynox\Ebay\Category\Action;

use Interop\Container\ContainerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class CategoryTreeFactory
{
    public function __invoke(ContainerInterface $container)
    {
        // TODO: Implement __invoke() method.
        return new CategoryTreeAction($container->get(TemplateRendererInterface::class));
    }
}
