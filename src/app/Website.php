<?php
namespace app;

use DI\Container;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\Exception\HttpNotFoundException;
use Slim\Factory\AppFactory;
use Slim\Psr7\Response;
use Slim\Views\Twig;
use app\action\CompositeXmlAction;
use app\action\GlobalIndexAction;
use app\action\P2IndexAction;
use app\action\RedirectToP2Action;
use app\action\VersionIndexAction;
use Throwable;

class Website
{
    public static function run()
    {
        $app = self::createApp('../web/p2');
        $app->run();
    }

    public static function createApp(string $p2DataPath): App
    {
        $container = new Container();
        $container->set('P2_DATA_PATH', $p2DataPath);
        $app = AppFactory::createFromContainer($container);
        self::registerTwigView($app);
        self::registerRoutes($app);
        self::installErrorHandling($app);
        return $app;
    }

    private static function registerTwigView(App $app)
    {
        $app->getContainer()->set(Twig::class, function (ContainerInterface $container) {
            return Twig::create(__DIR__ . '/../templates');
        });
    }
    
    private static function installErrorHandling(App $app)
    {
        $errorMiddleware = $app->addErrorMiddleware(true, true, true);
        $errorMiddleware->setErrorHandler(HttpNotFoundException::class, function (ServerRequestInterface $request, Throwable $exception, bool $displayErrorDetails) {
            $response = new Response();
            return $response->withStatus(404, 'not found');
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