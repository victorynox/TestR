<?php
/**
 * Created by PhpStorm.
 * User: victorynox
 * Date: 10.02.16
 * Time: 11:44
 */

namespace App\Action;

use App\Form\LoginForm;
use App\Form\RScriptForm;
use App\Form\SelectRScriptForm;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Zend\Diactoros\Stream;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Diactoros\Response\HtmlResponse;

class HelloAction
{
    private $template;
    private $rscriptConfig;

    public function __construct(TemplateRendererInterface $template, $config)
    {
        $this->template = $template;
        $this->rscriptConfig = $config['rscript_config'];

    }

    public function __invoke(Request $request, Response $response, callable $next)
    {

        $scriptsList = [];
        foreach ($this->rscriptConfig['scripts'] as $key => $value) {
            $paramsName = [];
            foreach($value['get'] as $name => $item){
                $paramsName[] = $name;
            }
            $scriptsList['scripts'][$key]['get'] = $value['get'];
            $scriptsList['scripts'][$key]['paramsName'] = $paramsName;
            $scriptsList['names'][] = $key;
            unset($temp);
        }
        $scriptsListJson = json_encode($scriptsList, JSON_UNESCAPED_UNICODE);
        $render = $this->template->render('app::hello', ['scriptsListJson' => $scriptsListJson]);
        $query['view']['render'] = $render;
        $query['view']['code'] = 200;
        $request = $request->withParsedBody($query);
        return $next($request, $response);

    }

}