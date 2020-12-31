<?php
namespace app\action;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use app\util\P2FileUtil;
use app\util\P2VersionUtil;

class CompositeXmlAction
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
        $fileName = basename($request->getUri()->getPath());
        
        return $this->view->render($response, $fileName, [
            'composite' => $composite,
            'version' => $version,
            'longVersionStr' => $longVersionStr
        ])
            ->withHeader('Content-Type', 'text/xml')
            ->withHeader('Last-Modified', date ('D, d M Y H:i:s T', $composite->timestamp / 1000));
    }
}