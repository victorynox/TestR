<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14.04.16
 * Time: 15:27
 */

namespace victorynox\Notification\Action;

use DTS\eBaySDK\Parser\XmlParser;
use DTS\eBaySDK\Trading\Types\AbstractResponseType;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use zaboy\rest\DataStore\Interfaces\DataStoresInterface;

class NotificationAction
{
    private $ebayConfig;
    /** @var  DataStoresInterface */
    private $store;

    public function __construct($ebayConfig, DataStoresInterface $store)
    {
        $this->ebayConfig = $ebayConfig;
        $this->store = $store;
    }

    public function __invoke(Request $request, Response $response, callable $next)
    {
        $xml = file_get_contents('php://input');
        $xml = preg_replace('/[\n\r]/', '', $xml);
        $xml = preg_replace('/>\s+/', '>', $xml);

        $rootBodyClass = 'DTS\eBaySDK\Trading\Types\AbstractResponseType';
        $parserBody = new XmlParser($rootBodyClass);

        $body = mb_strstr($xml, "<soapenv:Body>", false);
        $body = trim($body, "<soapenv:Body>");
        $body = mb_strstr($body, "</soapenv:Body>", true);
        $body = '<' . $body;
        /** @var AbstractResponseType $notification */
        $notification = $parserBody->parse($body);

        $notification->NotificationSignature = mb_strstr(
            $xml,
            '<ebl:NotificationSignature xmlns:ebl="urn:ebay:apis:eBLBaseComponents">',
            false
        );
        $notification->NotificationSignature = trim(
            $notification->NotificationSignature,
            '<ebl:NotificationSignature xmlns:ebl="urn:ebay:apis:eBLBaseComponents">'
        );
        $notification->NotificationSignature = mb_strstr(
            $notification->NotificationSignature,
            "</ebl:NotificationSignature>",
            true
        );

        $timestamp = mb_strstr($body, "<Timestamp>", false);
        $timestamp = trim($timestamp, "<Timestamp>");
        $timestamp = mb_strstr($timestamp, "</Timestamp>", true);

        if ($this->calculationSignature($timestamp) !== $notification->NotificationSignature) {
            throw new \Exception("Not Equalse signature", 403);
        }

        $item = [
            'add_date' => $notification->Timestamp->format("Y-m-d h:i:s"),
            'soapaction' => $notification->NotificationEventName,
            'data' => $body
        ];

        $this->store->create($item);


        return $response->withStatus(200);
    }

    private function calculationSignature($timestamp)
    {
        $signature = base64_encode(
            pack(
                'H*',
                md5(
                    "{$timestamp}
                    {$this->ebayConfig['credentials']['devId']}
                    {$this->ebayConfig['credentials']['appId']}
                    {$this->ebayConfig['credentials']['certId']}"
                )
            )
        );
        return $signature;
    }
}
