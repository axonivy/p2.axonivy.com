<?php
namespace app\action;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use app\util\P2FileUtil;

class VersionIndexAction
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke(Request $request, $response, $args)
    {
        $version = $args['version'];
        $p2DataPath = $this->container->get('settings')['p2DataPath'];
        $rootFolder = P2FileUtil::getRootFolder($request, $response, $p2DataPath, $version);
        $latestVersion = P2FileUtil::getLatestVersion($rootFolder);
        
        return $this->container->get('view')->render($response, 'version-index.html', [
            'version' => $version,
            'currentUri' => $this->container->get('request')->getUri(),
            'latestVersion' => $latestVersion
        ]);
    }
}