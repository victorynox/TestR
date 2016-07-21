<?php

/**
 * Created by PhpStorm.
 * User: root
 * Date: 21.07.16
 * Time: 12:38
 */


namespace victorynox\DataStore\Factory;

use Interop\Container\ContainerInterface;
use victorynox\DataStore\MultiInsertSql;
use zaboy\rest\TableGateway\Factory\TableGatewayAbstractFactory;
use Zend\Db\TableGateway\TableGateway;

class TableGatewayMultiInsertAbstractFactory extends TableGatewayAbstractFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new TableGateway($requestedName, $this->db, null, null, new MultiInsertSql($this->db, $requestedName));
    }
}
