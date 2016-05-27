<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 30.03.16
 * Time: 11:14
 */

namespace victorynox\Ebay\Category\Action;

use DTS\eBaySDK\Shopping\Services;
use DTS\eBaySDK\Shopping\Types;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use victorynox\DataStore\Cashable\CashableStores\GetAllInterface;
use Zend\Diactoros\Response\JsonResponse;

class GetEbayCategoryAction implements GetAllInterface
{
    /**
     * @var Services\ShoppingService $services
     */
    private $services;

    private $rootCategoryId;

    public function __construct($config)
    {
        $this->rootCategoryId = $config['rootID'];
        $this->services = new Services\ShoppingService([
            'credentials' => $config['credentials']
        ]);
    }

    public function __invoke(Request $request, Response $response, callable $next)
    {
        $type = $request->getAttribute("type");
        switch ($type) {
            case "tree":
                $category = $this->getCategoryTree($this->rootCategoryId);
                break;
            case "list":
                $category = $this->getCategoryList($this->rootCategoryId);
                break;
            default:
                throw new \Exception("Not set type of return ", 500);
        }

        return new JsonResponse([
            'category' => $category
        ]);
    }

    private function getCategoryTree($id)
    {

        $categoryList = [];

        $req = new Types\GetCategoryInfoRequestType([
            'CategoryID' => $id,
            'IncludeSelector' => "ChildCategories"
        ]);

        $resp = $this->services->getCategoryInfo($req);

        $categoryArray = $resp->CategoryArray->toArray();

        if (count($categoryArray["Category"]) < 2 && count($categoryArray["Category"]) > 0) {
            return $categoryArray["Category"][0];
        } else {
            foreach ($categoryArray["Category"] as $category) {
                if ($category["CategoryID"] == $id) {
                    $categoryList[] = $category;
                } else {
                    $categoryList[] = $this->getCategoryTree($category["CategoryID"]);
                }
            }
            return $categoryList;
        }
    }

    private function getCategoryList($id)
    {

        $categoryList = [];

        $req = new Types\GetCategoryInfoRequestType([
            'CategoryID' => $id,
            'IncludeSelector' => "ChildCategories"
        ]);

        $resp = $this->services->getCategoryInfo($req);

        $categoryArray = $resp->CategoryArray->toArray();

        if (count($categoryArray["Category"]) < 2 && count($categoryArray["Category"]) > 0) {
            return $categoryArray["Category"][0];
        } else {
            foreach ($categoryArray["Category"] as $category) {
                if ($category["CategoryID"] == $id) {
                    $categoryList[] = $category;
                } else {
                    //$categoryList[] = $this->getCategoryList($category["CategoryID"]);
                    $temp = $this->getCategoryList($category["CategoryID"]);
                    if (!isset($temp["CategoryID"])) {
                        foreach ($temp as $item) {
                            $categoryList[] = $item;
                        }
                    } else {
                        $categoryList[] = $temp;
                    }
                }
            }
            return $categoryList;
        }
    }

    /**
     * return
     * @return mixed
     */
    public function getAll()
    {
        $category = [];
        $categoryList = $this->getCategoryList($this->rootCategoryId);
        foreach ($categoryList as $item) {
            $temp['id'] = $item["CategoryID"];
            $temp['name'] = $item["CategoryName"];
            $temp['parentID'] = $item["CategoryParentID"];
            $temp['level'] = $item["CategoryLevel"];
            $temp['leafCategory'] = $item["LeafCategory"];
            $temp['idPath'] = $item["CategoryIDPath"];
            $category[] = $temp;
        }
        return $category;
    }
}
