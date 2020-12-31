<?php
namespace app;

use DI\Container;
use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use app\action\CompositeXmlAction;
use app\action\GlobalIndexAction;
use app\action\P2IndexAction;
use app\action\RedirectToP2Action;
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
        $container = new Container();
        $app = AppFactory::createFromContainer($container);
        self::registerTwigView($app);
        self::registerRoutes($app);
        return $app;
    }

    private static function registerTwigView(App $app)
    {
        $app->getContainer()->set(Twig::class, function (ContainerInterface $container) {
            
            // Instantiate and add Slim specific extension
            //$basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
            //$view->addExtension(new \Slim\Views\TwigExtension($container['router'], $basePath));
            return Twig::create(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'templates');
        });
    }

    private static function registerRoutes(App $app)
    {
        $app->get('/', RedirectToP2Action::class);
        $app->get('/p2/', GlobalIndexAction::class);
        $app->get('/p2/{version}/', VersionIndexAction::class);
        $app->get('/p2/{version}/p2.index', P2IndexAction::class);
        $app->get('/p2/{version}/compositeArtifacts.xml', CompositeXmlAction::class);
        $app->get('/p2/{version}/compositeContent.xml', CompositeXmlAction::class);
    }
}