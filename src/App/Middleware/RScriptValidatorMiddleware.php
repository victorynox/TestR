<?php
/**
 * Created by PhpStorm.
 * User: victorynox
 * Date: 11.02.16
 * Time: 13:51
 */

namespace App\Middleware;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use SebastianBergmann\RecursionContext\Exception;

class RScriptValidatorMiddleware
{

    private $rscriptConfig;
    private $isValid;

    public function __construct($config)
    {
        $this->isValid = true;
        $this->rscriptConfig = $config['rscript_config'];

    }

    /**
     * @param Request $request
     * @param Response $response
     * @param callable $next
     * @return callable ;
     * @throws \Exception
     */
    public function __invoke(Request $request, Response $response, callable $next)
    {

        $scriptName = $request->getAttribute('script_name');
        $query = $request->getQueryParams();
        $validQuery = array();

        if(!isset($this->rscriptConfig[$scriptName])){
            $this->isValid = false;
            throw new \Exception('invalid script name:' . $scriptName);
        }else{
            foreach($this->rscriptConfig[$scriptName]['get'] as $key => $value){
                if(isset($query[$key])){
                    switch ($value){
                        case 'int':{
                            if((float)$query[$key] == 0 or !is_int((int)$query[$key])){
                                $this->isValid = false;
                            }
                            break;
                        }
                        case  'string':{
                            if((string)$query[$key] == '' or !is_string((string)$query[$key])){
                                $this->isValid = false;
                            }
                            break;
                        }
                        case 'float':{
                        if((float)$query[$key] == 0 or !is_float((float)$query[$key])){
                            $this->isValid = false;
                        }
                            break;
                        }
                    }
                }else{
                    $this->isValid = false;
                }

                if($this->isValid){
                    $validQuery[$key] = $value;
                }else{
                    throw new \Exception('invalid value: [' . $key .']=>' . $value);
                }
            }
        }

        $request->withQueryParams($validQuery);
        return $next($request, $response);

    }

}