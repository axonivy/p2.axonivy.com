<?php
namespace app\action;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use app\util\P2FileUtil;
use app\util\P2VersionUtil;

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
        $longVersionStr = P2VersionUtil::toLongversionString($version);
        $p2DataPath = $this->container->get('settings')['p2DataPath'];
        $rootFolder = P2FileUtil::getRootFolder($request, $response, $p2DataPath, $version);
        $composite = P2FileUtil::getFolders($rootFolder);
        $latestVersion = P2FileUtil::getLatestVersion($rootFolder);
        
        return $this->container->get('view')->render($response, 'version-index.html', [
            'version' => $version,
            'longVersionStr' => $longVersionStr,
            'currentUri' => $this->container->get('request')->getUri(),
            'latestVersion' => $latestVersion,
            'composite' => $composite
        ]);
    }
}