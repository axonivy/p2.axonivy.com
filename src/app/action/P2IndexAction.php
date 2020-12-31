<?php
namespace app\action;

use Psr\Http\Message\ServerRequestInterface as Request;
use app\util\P2FileUtil;
use Slim\Views\Twig;

class P2IndexAction
{
    private $view;
    
    public function __construct(Twig $view)
    {
        $this->view = $view;
    }

    public function __invoke(Request $request, $response, $args)
    {
        $version = $args['version'];
        $p2DataPath = P2_DATA_PATH;
        $rootFolder = P2FileUtil::getRootFolder($request, $response, $p2DataPath, $version);
        
        return $this->view
            ->render($response, 'p2.index')
            ->withHeader('Content-Type', 'text/plain');
    }
}