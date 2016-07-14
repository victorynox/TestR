<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14.07.16
 * Time: 12:12
 */

namespace victorynox\Ebay\Trading\Action;

use DTS\eBaySDK\Constants;
use DTS\eBaySDK\Trading\Services\TradingService;
use DTS\eBaySDK\Trading\Types\CustomSecurityHeaderType;
use DTS\eBaySDK\Trading\Types\GetItemTransactionsRequestType;
use DTS\eBaySDK\Trading\Types\TransactionType;
use DTS\eBaySDK\Trading\Types\UserIdPasswordType;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Diactoros\Response\JsonResponse;

class GetItemTransactionsAction
{
    protected $service;

    protected $eBayAuthToken;

    protected $credentials;

    public function __construct($credentials, $eBayAuthToken)
    {
        $this->eBayAuthToken = $eBayAuthToken;
        $this->credentials = $credentials;
        try {
            $this->service = new TradingService([
                'siteId' => Constants\SiteIds::US,
                'credentials' => $credentials
            ]);

        } catch (\Exception $e) {
            $e->getMessage();
        }
    }

    public function __invoke(Request $request, Response $response, callable $next)
    {

        $getItemTransactionsRequest = new GetItemTransactionsRequestType([
            'ItemID' => '271666197472'
        ]);

        $getItemTransactionsRequest->RequesterCredentials = new CustomSecurityHeaderType();
        $getItemTransactionsRequest->RequesterCredentials->eBayAuthToken = $this->eBayAuthToken;
        $getItemTransactionsRequest->RequesterCredentials->Credentials = new UserIdPasswordType([
            'AppId' => $this->credentials['appId'],
            'DevId' => $this->credentials['devId'],
            'AuthCert' => $this->credentials['certId'],
        ]);

        $getItemTransactionsResponse = $this->service->getItemTransactions($getItemTransactionsRequest);
        $status = 500;
        $report = [];

        if ($getItemTransactionsResponse->Ack === 'Failure') {

            foreach ($getItemTransactionsResponse->Errors as $error) {
                $item = [];
                $item['ErrorCode'] = $error->ErrorCode;
                $item['SeverityCode'] = $error->SeverityCode;
                $item['LongMessage'] = $error->LongMessage;
                $report['errors'][] = $item;
            }

        } else {
            $transactions = $getItemTransactionsResponse->TransactionArray;

            foreach ($transactions->Transaction as $transaction) {
                $item = [];
                $item['QuantityPurchased'] = $transaction->QuantityPurchased;
                $item['TransactionPrice'] = $transaction->TransactionPrice->value;
                $item['PaidTime'] = $transaction->PaidTime;
                $item['TotalPrice'] = $transaction->TotalPrice;
                $item['AmountPaid'] = $transaction->AmountPaid->value;
                $item['ConvertedTransactionPrice'] = $transaction->ConvertedTransactionPrice->value;
                $item['BuyerGuaranteePrice'] = $transaction->BuyerGuaranteePrice->value;
                $item['CreatedDate'] = $transaction->CreatedDate;
                $report[] = $item;
            }
            $status = 200;
        }


        /** @var TransactionType $transaction */
        return new JsonResponse($report, $status);
    }
}
