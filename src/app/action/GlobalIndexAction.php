<?php
namespace app\action;

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class GlobalIndexAction
{
    private $view;

    public function __construct(Twig $view)
    {
        $this->view = $view;
    }

    public function __invoke(Request $request, $response, $args)
    {
        return $this->view->render($response, 'global-index.html');
    }
}
