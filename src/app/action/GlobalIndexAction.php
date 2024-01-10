<?php
namespace app\action;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use app\util\P2FileUtil;

class GlobalIndexAction
{
    private $view;

    private ContainerInterface $container;

    public function __construct(Twig $view, ContainerInterface $container)
    {
        $this->view = $view;
        $this->container = $container;
    }

    public function __invoke(Request $request, $response, $args)
    {
        $p2DataPath = $this->container->get('P2_DATA_PATH');
        $folders = P2FileUtil::getRootFolders($p2DataPath);
        $folders = array_map(fn($f) => self::toFolder($f), $folders);
        usort($folders, fn(Folder $a, Folder $b) => version_compare($b->path, $a->path));
        return $this->view->render($response, 'global-index.html', [
            'folders' => $folders
        ]);
    }

    private static function toFolder(string $folder): Folder {
        $path = basename($folder);
        $name = str_replace('-', ' ', $path);
        return new Folder($name, $path);
    }
}

class Folder {

    public string $name;
    public string $path;

    public function __construct(string $name, string $path)
    {
        $this->name = $name;
        $this->path = $path;
    }
}
