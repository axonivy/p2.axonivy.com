<?php
namespace app;

use Slim\App;
use Slim\Views\Twig;
use app\action\CompositeXmlAction;
use app\action\GlobalIndexAction;
use app\action\P2IndexAction;
use app\action\VersionIndexAction;

class Website
{

    public static function run()
    {
        $app = self::createApp('../web/p2');
        $app->run();
    }

    public static function createApp(string $p2DataPath): App
    {
        define('P2_DATA_PATH', $p2DataPath);
        $config = [
            'settings' => [
                'p2DataPath' => $p2DataPath
            ]
        ];
        $app = new App($config);
        self::registerTwigView($app);
        self::registerRoutes($app);
        return $app;
    }

    private static function registerTwigView(App $app)
    {
        $container = $app->getContainer();
        // Register component on container
        $container['view'] = function ($container) {
            $view = new Twig(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'templates');
            // Instantiate and add Slim specific extension
            $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
            $view->addExtension(new \Slim\Views\TwigExtension($container['router'], $basePath));
            return $view;
        };
    }

    private static function registerRoutes(App $app)
    {
        $app->get('/p2/', GlobalIndexAction::class);
        $app->get('/p2/{version}/', VersionIndexAction::class);
        $app->get('/p2/{version}/p2.index', P2IndexAction::class);
        $app->get('/p2/{version}/compositeArtifacts.xml', CompositeXmlAction::class);
        $app->get('/p2/{version}/compositeContent.xml', CompositeXmlAction::class);
    }
}