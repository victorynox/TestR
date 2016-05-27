<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 25.04.16
 * Time: 10:43
 */

namespace victorynox\Util\Tree;

use RecursiveIterator;

class TreeNodeComposite extends TreeNodeAbstract
{
    public function getComposite()
    {
        return $this;
    }

    public function toJSON()
    {
        $json = '{"name":"'.$this->getName() . '", "children":[';
        foreach ($this->child as $child) {
            /** @var $child TreeNodeAbstract */
            $json .= $child->toJSON() . ',';
        }
        $json = trim($json, ',');
        $json .= ']}';
        return $json;
    }
    
}
