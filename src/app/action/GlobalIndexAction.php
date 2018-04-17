<?php
namespace app\action;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

class GlobalIndexAction
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke(Request $request, $response, $args)
    {
        $p2DataPath = $this->container->get('settings')['p2DataPath'];
        
        return $this->container->get('view')
            ->render($response, 'global-index.html');
    }
}