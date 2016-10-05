<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 08.07.16
 * Time: 15:11
 */

namespace victorynox\AnalyticReports\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Xiag\Rql\Parser\Node\Query\LogicOperator\AndNode;
use Xiag\Rql\Parser\Node\Query\ScalarOperator\EqNode;
use Xiag\Rql\Parser\Query;
use zaboy\rest\DataStore\Interfaces\DataStoresInterface;
use zaboy\rest\Middleware;
use zaboy\rest\RestException;
use zaboy\rest\RqlParser\RqlParser;
use Zend\Authentication\Storage\Session;


class CDSManagerMiddleware extends Middleware\DataStoreAbstract
{
    /** @var  DataStoresInterface */
    protected $cdsManagerStore;

    /** @var  DataStoresInterface */
    protected $dataStore;

    /** @var DataStoresInterface */
    protected $cds;


    public function __construct(DataStoresInterface $dataStores, DataStoresInterface $cdsManagerStore, DataStoresInterface $cds)
    {
        $this->cdsManagerStore = $cdsManagerStore;
        $this->cds = $cds;
        parent::__construct($dataStores);
    }

    public function __invoke(Request $request, Response $response, callable $next = null)
    {
        $httpMethod = $request->getMethod();
        $cdsId = $request->getAttribute('Primary-Key-Value');
        switch (true) {
            case $httpMethod === 'POST' && !($cdsId): {
                list($request, $response) = $this->methodPostWithoutId($request, $response);
                break;
            }
            /*case $httpMethod === 'PATCH' && $cdsId: {
                list($request, $response) = $this->methodPatchWithId($request, $response);
                break;
            }*/
            default : {
                throw new RestException(
                    'Method must be POST or PATCH . '
                    . $request->getMethod() . ' given'
                );
            }
        }

        if ($next) {
            return $next($request, $response);
        }

        return $response;
    }

    protected function methodPostWithoutId(Request $request, Response $response)
    {
        $body = $request->getParsedBody();

        if (isset($body['scriptName'])) {
            $scriptName = $body['scriptName'];
        } else {
            throw new \Exception('script name not set');
        }

        if (isset($body['query'])) {
            $queryString = $body['query'];
            $query = RqlParser::rqlDecode($body['query']);
        } else {
            $query = new Query();
            $queryString = "";
        }

        $cdsId = $this->generateCsdId($scriptName, $queryString);

        if (!$this->checkCds($cdsId)) {

            $data = $this->getData($scriptName, $query);

            $this->cdsManagerStore->create([
                'cds_id' => $cdsId,
                'script_name' => $scriptName,
                'query' => $queryString,
                'freshness_date' => (new \DateTime())->format("Y-m-d H:i:s")
            ]);

            foreach ($data as &$item) {
                $item['cds_id'] = $cdsId;
                $item['data_id'] = $item['id'];
                unset($item['id']);
            }

            $this->cds->create($data);
        }else{
            $cdsConf = $this->getCdsConf($cdsId);
            $freshnessDate = \DateTime::createFromFormat("Y-m-d H:i:s", $cdsConf['freshness_date']);
            $currentDate = new \DateTime();
            $dateDiff = $currentDate->diff($freshnessDate);
            if($dateDiff->days > 0){
                $request = $request->withAttribute('Primary-Key-Value', $cdsId);
                return $this->methodPatchWithId($request, $response);
            }
        }


        $request = $request->withAttribute('CDS-ID', $cdsId);
        $request = $request->withAttribute('Response-Body',['cdsId' => $cdsId]);

        $response = $response->withStatus(200);

        return array($request, $response);
    }

    public function generateCsdId($scriptName, $query)
    {
        //TODO generate token by query string and scriptName
        $cdsId = md5("{$scriptName}{$query}");

        return $cdsId;
    }

    public function checkCds($cdsId)
    {
        return empty($this->getCdsConf($cdsId)) ? false : true;
    }

    private function getCdsConf($cdsId)
    {
        $cdsIdQuery = new Query();
        $cdsIdQuery->setQuery(new EqNode('cds_id', $cdsId));

        /** @var array $resp */
        $resp = $this->cdsManagerStore->query($cdsIdQuery);

        return empty($resp) ? [] : $resp[0];
    }

    protected function getData($scriptName, Query $query)
    {
        $reqQuery = new Query();
        if ($query->getQuery() !== null) {
            $reqQuery->setQuery(new AndNode([new  EqNode('scriptName', $scriptName), $query->getQuery()]));
        } else {
            $reqQuery->setQuery(new  EqNode('scriptName', $scriptName));
        }

        return $this->dataStore->query($reqQuery);
    }

    protected function methodPatchWithId(Request $request, Response $response)
    {
        $cdsId = $request->getAttribute('Primary-Key-Value');

        $cdsConf = $this->getCdsConf($cdsId);

        if (empty($cdsConf)) {
            throw new \Exception("this cds id not found");
        }

        if ($cdsConf['query'] !== '') {
            $query = RqlParser::rqlDecode($cdsConf['query']);
        } else {
            $query = new Query();
        }

        $data = $this->getData($cdsConf['script_name'], $query);

        $query = new Query();
        $query->setQuery(new EqNode('cds_id', $cdsId));
        $items = $this->cds->query($query);

        foreach ($items as $item){
            $this->cds->delete($item['id']);
        }

        foreach ($data as &$item) {
            $item['cds_id'] = $cdsId;
            $item['data_id'] = $item['id'];
            unset($item['id']);
        }

        $this->cds->create($data);

        $this->cdsManagerStore->update([
            'id' => $cdsConf['id'],
            /*'cds_id' => $cdsConf['cds_id'],
            'script_name' => $cdsConf['script_name'],
            'query' => $cdsConf['query'],*/
            'freshness_date' => (new \DateTime())->format("Y-m-d H:i:s")
        ]);

        $request = $request->withAttribute('CDS-ID', $cdsId);
        $request = $request->withAttribute('Response-Body',['cdsId' => $cdsId]);

        $response = $response->withStatus(200);

        return array($request, $response);
    }


}