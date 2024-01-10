<?php
namespace app\action;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use app\util\P2FileUtil;

class P2IndexAction
{
    private $view;
    private ContainerInterface $container;

    public function __construct(Twig $view, ContainerInterface $container)
    {
        $this->view = $view;
        $this->container = $container;
    }

    public function __invoke(Request $request, $response, $args)
    {
        $version = $args['version'];
        $p2DataPath = $this->container->get('P2_DATA_PATH');
        // i guess this is needed to throw 404
        P2FileUtil::getRootFolder($request, $response, $p2DataPath, $version);

        return $this->view
            ->render($response, 'p2.index')
            ->withHeader('Content-Type', 'text/plain');
    }
}
