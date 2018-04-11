<?php
namespace app;

use Slim\App;
use Slim\Views\Twig;
use app\action\CompositeXmlAction;
use app\action\P2IndexAction;

class Website
{

    public static function run()
    {
        $app = self::createApp('../templates');
        $app->run();
    }

    public static function createApp(string $templateDirectory): App
    {
        $app = new App();
        self::registerTwigView($app, $templateDirectory);
        self::registerRoutes($app);
        return $app;
    }

    private static function registerTwigView(App $app, string $templateDirectory)
    {
        $container = $app->getContainer();
        // Register component on container
        $container['view'] = function ($container) use ($templateDirectory) {
            $view = new Twig($templateDirectory);
            // Instantiate and add Slim specific extension
            $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
            $view->addExtension(new \Slim\Views\TwigExtension($container['router'], $basePath));
            return $view;
        };
    }

    private static function registerRoutes(App $app)
    {
        $app->get('/{version}/p2.index', P2IndexAction::class);
        $app->get('/{version}/compositeArtifacts.xml', CompositeXmlAction::class);
        $app->get('/{version}/compositeContent.xml', CompositeXmlAction::class);
    }
}