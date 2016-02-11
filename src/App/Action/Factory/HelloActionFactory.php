<?php
/**
 * Created by PhpStorm.
 * User: victorynox
 * Date: 10.02.16
 * Time: 12:07
 */

namespace App\Action\Factory;

use Interop\Container\ContainerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
use App\Action\HelloAction;


class HelloActionFactory
{

    public function __invoke(ContainerInterface $interface)
    {
        return new HelloAction($interface->get(TemplateRendererInterface::class));
    }

}