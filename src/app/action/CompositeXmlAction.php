<?php
namespace app\action;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use app\util\P2FileUtil;

class CompositeXmlAction
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
        $composite = P2FileUtil::getFolders($rootFolder);
        $fileName = basename($request->getUri()->getPath());
        
        return $this->container->get('view')
        ->render($response, $fileName, [
            'composite' => $composite,
            'version' => $version
        ])
            ->withHeader('Content-Type', 'text/xml')
            ->withHeader('Last-Modified', date ('D, d M Y H:i:s T', $composite->timestamp / 1000));
    }
}