<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 30.03.16
 * Time: 10:23
 */

namespace victorynox\Ebay\Category\Action;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use victorynox\DataStore\Cashable\CashableStores\CashableStoreAbstract;
use Zend\Diactoros\Response\JsonResponse;

/** TODO сделать реализацию доступа через лайк */
class UpdateCategoryAction
{

    protected $cashableStore;

    public function __construct(CashableStoreAbstract $cashableStore)
    {
        $this->cashableStore = $cashableStore;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param callable $next
     * @return JsonResponse
     */
    public function __invoke(Request $request, Response $response, callable $next)
    {
        try {
            $this->cashableStore->refresh();
            $report = ['status' => 'Refreshed'];
        } catch (\Exception $e) {
            $report = [
                'status' => 'Error',
                'message' => $e->getMessage(),
            ];

        }

        return new JsonResponse($report);
    }

}