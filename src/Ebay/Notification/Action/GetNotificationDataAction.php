<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 22.04.16
 * Time: 17:43
 */

namespace victorynox\Ebay\Notification\Action;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use victorynox\Util\Tree\TreeNodeAbstract;
use victorynox\Util\Tree\TreeNodeComposite;
use victorynox\Util\Tree\TreeNodeLeaf;
use Xiag\Rql\Parser\Node\Query\ScalarOperator\EqNode;
use Xiag\Rql\Parser\Query;
use zaboy\res\DataStores\DataStoresInterface;
use Zend\Diactoros\Response\JsonResponse;

class GetNotificationDataAction
{
    /** @var  DataStoresInterface */
    private $notificationStore;

    public function __construct(DataStoresInterface $notificationStore)
    {
        $this->notificationStore = $notificationStore;
    }

    public function __invoke(Request $request, Response $response, callable $next)
    {
        $id = $request->getAttribute('id');
        $query = new Query();
        $query->setQuery(new EqNode('id', $id));
        $notification = $this->notificationStore->query($query);
        $data = new \SimpleXMLIterator($notification[0]['data']);
        //$notificationTree = new TreeNodeComposite('root');

        $notificationTree = $this->createTree('root', $data);
        if (!$notificationTree) {
            throw new \Exception("tree is null");
        }
        $json = '[' . $notificationTree->toJSON() . ']';
        $response->getBody()->write($json);
        return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
    }

    /**
     * @param $name
     * @param $item
     * @return TreeNodeAbstract|null
     */
    private function createTree($name, $item)
    {

        if ($item instanceof \Traversable or is_array($item)) {
            $composite = new TreeNodeComposite($name);
            foreach ($item as $key => $value) {
                $composite->setChild($this->createTree($key, $value));
            }
            if (!$composite->hasChild(0)) {
                foreach ((array)$item as $key => $value) {
                    if (is_array($value)) {
                        $composite->setChild($this->createTree($key, $value));
                    } else {
                        $composite->setChild(new TreeNodeLeaf($value));
                    }
                }
            }
        } else {
            $composite = new TreeNodeComposite($name);
            $composite->setChild(new TreeNodeLeaf($item));
        }
        return $composite;
    }
}
