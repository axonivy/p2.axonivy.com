<?php
require '../../vendor/autoload.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App();

$app->get('/LE/compositeArtifacts.xml', function (Request $request, Response $response, array $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");
    return $response;
});

$app->get('/{all}/p2.index', function (Request $request, Response $response, array $args) {
    $content = "
        version=1
        metadata.repository.factory.order=compositeArtifacts.xml,content.xml.xz,content.xml,\!
        artifact.repository.factory.order=artifacts.xml.xz,artifacts.xml,\!
    ";
    $response->getBody()->write($content);
    return $response;
});

$app->run();
