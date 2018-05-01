<?php
namespace app\action;

use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class RedirectToP2Action
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response, $args)
    {
        return $response->withRedirect('/p2/', 302);
    }
}