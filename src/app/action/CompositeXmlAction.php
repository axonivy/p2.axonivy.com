<?php
namespace app\action;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use app\util\P2FileUtil;

class CompositeXmlAction
{
    private Twig $view;
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
        $rootFolder = P2FileUtil::getRootFolder($request, $response, $p2DataPath, $version);
        $composite = P2FileUtil::getFolders($rootFolder);
        $fileName = basename($request->getUri()->getPath());
        $timestamp =  $composite->timestamp / 1000;
        return $this->view->render($response, $fileName, [
            'composite' => $composite,
            'version' => $version
        ])
            ->withHeader('Content-Type', 'text/xml')
            ->withHeader('Last-Modified', date('D, d M Y H:i:s T', (int) $timestamp));
    }
}
