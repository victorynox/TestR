<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 10.03.16
 * Time: 12:34
 */

namespace App\DataStore;

use App\DataStore\ConditionBuilder\FiqlConditionBuilder;
use Xiag\Rql\Parser\Node\AbstractQueryNode;
use zaboy\res\DataStore\ConditionBuilder\RqlConditionBuilder;
use zaboy\res\DataStore\HttpClient;
use Xiag\Rql\Parser\Query;
use zaboy\res\DataStores\ConditionBuilderAbstract;
use zaboy\res\DataStores\DataStoresException;
use Zend\Http\Client;
use zaboy\res\Rql\QueryResolver;


class RHttpClient extends HttpClient
{

    //eq(a,2)&or(eq(b,2), eq(b,3))
    //or(eq(a,2)&eq(b,2),eq(a,2)&eq(b,3))
    protected function initHttpClient($method, Query $query = null, $id = null, $ifMatch = false)
    {
        $url = !$id ? $this->url : $this->url . '/' . $id;
        if (isset($query)) {
            $queryString = $this->rqlEncode($query);
            $url = rtrim($url, '/') . '/?' . $queryString;
        }

        $this->options['timeout'] = 30;

        $httpClient = new Client($url, $this->options);
        $headers['Content-Type'] =  'application/json';
        $headers['Accept'] =  'application/json';
        if ($ifMatch) {
            $headers['If-Match'] =  '*';
        }
        $httpClient->setHeaders($headers);
        if (isset($this->login) && isset($this->password)) {
            $httpClient->setAuth($this->login, $this->password);
        }
        $httpClient->setMethod($method);
        return $httpClient;
    }

    public function __construct($url, $options = null, ConditionBuilderAbstract $conditionBuilder = null)
    {
        parent::__construct($url, $options);
        $this->url = rtrim(trim($url),'/');
        if ( isset($conditionBuilder)) {
            $this->_conditionBuilder = $conditionBuilder;
        }  else {
            $this->_conditionBuilder = new RqlConditionBuilder;
        }
    }


    /*public function  rqlEncode(Query $query)
    {

        $rqlQueryString = $this->getQueryWhereConditioon($query->getQuery());
        $rqlQueryString = $this->makeLimit($query, $rqlQueryString);
        $rqlQueryString = $this->makeSort($query, $rqlQueryString);
        $rqlQueryString = $this->makeSelect($query, $rqlQueryString);
        var_dump('Astruct where -------->' . $rqlQueryString);
        return ltrim($rqlQueryString,'&');
    }*/
}