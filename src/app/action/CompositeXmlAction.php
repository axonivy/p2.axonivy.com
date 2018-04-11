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
        $rootFolder = P2FileUtil::getRootFolder($request, $response, $version);
        $composite = P2FileUtil::getFolders($rootFolder);
        $fileName = basename($request->getUri()->getPath());
        
        return $this->container->get('view')
            ->render($response, $fileName, [
            'composite' => $composite
        ])
            ->withHeader('Content-Type', 'text/xml');
    }
}