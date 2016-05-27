<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 25.04.16
 * Time: 10:43
 */

namespace victorynox\Util\Tree;

class TreeNodeLeaf extends TreeNodeAbstract
{
    public function toJSON()
    {
        return '{"name":"' . urlencode($this->getName()) . '"}';
    }
}
