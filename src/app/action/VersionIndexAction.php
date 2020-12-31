<?php
namespace app\action;

use Psr\Http\Message\ServerRequestInterface as Request;
use app\util\P2FileUtil;
use app\util\P2VersionUtil;
use Slim\Views\Twig;

class VersionIndexAction
{
    private $view;
    
    public function __construct(Twig $view)
    {
        $this->view = $view;
    }

    public function __invoke(Request $request, $response, $args)
    {
        $version = $args['version'];
        $longVersionStr = P2VersionUtil::toLongversionString($version);
        $p2DataPath = P2_DATA_PATH;
        $rootFolder = P2FileUtil::getRootFolder($request, $response, $p2DataPath, $version);
        $composite = P2FileUtil::getFolders($rootFolder);
        
        $artifactsPerRepo = array();
        foreach($composite->locations as $repo)
        {
            $artifactsXml=$rootFolder. DIRECTORY_SEPARATOR .$repo. DIRECTORY_SEPARATOR . "artifacts.xml";
            $artifacts=P2FileUtil::getP2ArtifactsFromXml($artifactsXml);
            $artifactsPerRepo[$repo] = $artifacts;
        }
        
        $latestVersion = P2FileUtil::getLatestVersion($rootFolder);
        
        return $this->view->render($response, 'version-index.html', [
            'version' => $version,
            'longVersionStr' => $longVersionStr,
            'currentUri' => $this->container->get('request')->getUri(),
            'latestVersion' => $latestVersion,
            'composite' => $composite,
            'artifacts' => $artifactsPerRepo
        ]);
    }
}