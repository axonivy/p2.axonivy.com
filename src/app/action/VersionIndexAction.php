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
        $versionStr = self::versionString($version);
        $p2DataPath = $this->container->get('settings')['p2DataPath'];
        $rootFolder = P2FileUtil::getRootFolder($request, $response, $p2DataPath, $version);
        $latestVersion = P2FileUtil::getLatestVersion($rootFolder);
        
        return $this->container->get('view')->render($response, 'version-index.html', [
            'version' => $version,
            'versionStr' => $versionStr,
            'currentUri' => $this->container->get('request')->getUri(),
            'latestVersion' => $latestVersion
        ]);
    }
    
    private function versionString($version) : string
    {
        switch ($version) {
            case 'leading':
                return 'Leading Edge';
            case 'sprint':
                return 'Sprint Release';
            case 'nightly':
                return 'Nightly Build';
        }
        return $version;
    }
}