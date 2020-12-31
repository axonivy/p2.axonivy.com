<?php
namespace app\action;

class RedirectToP2Action
{
    public function __invoke($request, $response, $args)
    {
        return $response->withRedirect('/p2/', 302);
    }
}
