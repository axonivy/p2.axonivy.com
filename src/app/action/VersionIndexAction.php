<?php
namespace app\action;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use app\util\P2FileUtil;

class VersionIndexAction
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
        $latestVersion = P2FileUtil::getLatestVersion($rootFolder);
        return $this->view->render($response, 'version-index.html', [
            'version' => $version,
            'latestVersion' => $latestVersion,
            'composite' => $composite
        ]);
    }
}
