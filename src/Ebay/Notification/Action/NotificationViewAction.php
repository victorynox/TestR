<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 21.04.16
 * Time: 15:23
 */

namespace victorynox\Ebay\Notification\Action;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Zend\Expressive\Template\TemplateRendererInterface;

class NotificationViewAction
{
    private $renderer;

    public function __construct(TemplateRendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param callable $next
     * @return static
     * @TODO add view in request attribute end return $next
     */
    public function __invoke(Request $request, Response $response, callable $next)
    {
        $render = $this->renderer->render('ebay::notification');

        $response->getBody()->write($render);
        return $response->withStatus(200);
    }
}
