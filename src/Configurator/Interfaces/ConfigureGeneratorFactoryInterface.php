<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 05.08.16
 * Time: 13:34
 */

namespace victorynox\Configurator\Interfaces;

use Interop\Container\ContainerInterface;

interface ConfigureGeneratorFactoryInterface
{

    public function __construct(ContainerInterface $container);

    /**
     * @param $requestedName
     * @return ConfigurableInterface
     */
    public function __invoke($requestedName);
}
