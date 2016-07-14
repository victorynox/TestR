<?php
/**
 * Created by PhpStorm.
 * User: victorynox
 * Date: 10.02.16
 * Time: 12:07
 */

namespace victorynox\AnalyticReports\Action;

use Interop\Container\ContainerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class HelloActionFactory
{

    public function __invoke(ContainerInterface $interface)
    {
        return new HelloAction($interface->get(TemplateRendererInterface::class), $interface->get('config'));
    }
}
