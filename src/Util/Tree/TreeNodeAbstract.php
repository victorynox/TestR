<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 25.04.16
 * Time: 10:24
 */

namespace victorynox\Util\Tree;

abstract class TreeNodeAbstract  
{
    /**
     * @var array
     */
    protected $child;
    /**
     * @var string
     */
    private $name;

    /**
     * TreeNodeAbstract constructor.
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;

        $this->child = [];
    }

    /**
     * @return TreeNodeAbstract|null
     */
    public function getComposite()
    {
        return null;
    }

    /**
     * @param int $id
     * @return TreeNodeAbstract
     */
    public function getChild($id)
    {
        return $this->child[$id];
    }

    /**
     * @param TreeNodeAbstract $nodeAbstract
     */
    public function setChild(TreeNodeAbstract $nodeAbstract)
    {
        $this->child[] = $nodeAbstract;
    }

    /**
     * @param int $id
     */
    public function removeChild($id)
    {
        unset($this->child[$id]);
    }

    public function hasChild($id)
    {
        return isset($this->child[$id]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    abstract public function toJSON();
}
