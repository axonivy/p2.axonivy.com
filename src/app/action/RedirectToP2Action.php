<?php
namespace app\action;

use Psr\Http\Message\ResponseInterface;

class RedirectToP2Action
{
    public function __invoke($request, ResponseInterface $response, $args)
    {
        return $response->withHeader('Location', '/p2/')->withStatus(302);
    }
}
