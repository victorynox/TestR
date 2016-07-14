<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 24.05.16
 * Time: 15:06
 */

namespace victorynox\Ebay\Finding\Action;

use DTS\eBaySDK\Finding\Services\FindingService;
use DTS\eBaySDK\Finding\Types\FindItemsIneBayStoresRequest;
use DTS\eBaySDK\Finding\Types\SearchItem;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Diactoros\Response\JsonResponse;

class FindItemsIneBayStoreAction
{
    private $service;

    /*public function __construct()
    {

    }*/

    public function __invoke(Request $request, Response $response, callable $next)
    {
        $this->service = new FindingService([
            'credentials' => [
                'appId' => '',
                'certId' => '',
                'devId' => '',
            ]
        ]);

        $findingRequest = new FindItemsIneBayStoresRequest([
            'storeName' => 'Rollun',
            'keywords' => 'EFX MotoHammer Radial ATV Tire 27x9-14 ARCTIC'
        ]);

        $report = [];
        $report['time']['start'] = new \DateTime();
        $findingResponse = $this->service->findItemsIneBayStores($findingRequest);
        $report['time']['end'] = new \DateTime();
        $report['ack'] = $findingResponse->ack;
        $report['error'] = $findingResponse->errorMessage;
        $report['itemSearchURL'] = $findingResponse->itemSearchURL;

        /** @var SearchItem $item */
        if ($findingResponse->searchResult->count > 0) {
            $report['searchResult']['count'] = $findingResponse->searchResult->count;
            foreach ($findingResponse->searchResult->item as $item) {
                $temp['id'] = $item->itemId;
                $temp['title'] = $item->title;
                $temp['subtitle'] = $item->title;
                $temp['productId'] = $item->productId;
                $report['searchResult']['items'][] = $temp;
            }
        }
        return new JsonResponse($report);
    }
}
