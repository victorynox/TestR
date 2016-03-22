<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 10.03.16
 * Time: 16:16
 */

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use zaboy\rest\Middleware\RequestDecoder as Decoder;

class RequestDecoder extends Decoder
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        // @see https://github.com/SitePen/dstore/blob/21129125823a29c6c18533e7b5a31432cf6e5c56/src/Rest.js
        $overwriteModeHeader = $request->getHeader('If-Match');

        if(is_array($overwriteModeHeader) and isset($overwriteModeHeader[0]) and $overwriteModeHeader[0] === '*'){
            $overwriteMode = true;
        }else{
            $overwriteMode = false;

        }
        #$overwriteMode = $overwriteModeHeader[0] === '*' ? true : false;
        $request = $request->withAttribute('Overwrite-Mode', $overwriteMode);

        $putDefaultPosition = $request->getHeader('Put-Default-Position'); //'start' : 'end'
        if (isset($putDefaultPosition)) {
            $request = $request->withAttribute('Put-Default-Position', $putDefaultPosition);
        }
        // @see https://github.com/SitePen/dstore/issues/42
        $putBeforeHeader = $request->getHeader('Put-Before');
        $putBefore = !empty($putBeforeHeader);
        $request = $request->withAttribute('Put-Before', $putBefore);

        $contenttype = $request->getHeader('Content-Type');
        if (is_array($contenttype) and isset($contenttype[0]) and false !== strpos($contenttype[0], 'json')) {
            $body = $this->jsonDecode($request->getBody()->__toString());
            $request = $request->withParsedBody($body);
        } else {
            //todo XML?
        }

        //TODO fictive first param
        $query = $request->getUri()->getQuery();
        $query = 'q2w=0&' . $query;
        $request = $request->withUri($request->getUri()->withQuery($query));

        if ($next) {
            return $next($request, $response);
        }

        return $response;
    }
}