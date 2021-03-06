<?php

namespace MODXDocs\Middlewares;

use Slim\Http\Response;
use Slim\Http\Request;

class RequestMiddleware
{
    public function __invoke(Request $request, Response $response, $next)
    {
        $uri = $request->getUri();
        $path = $uri->getPath();

        if ($path !== '/' && substr($path, -1) === '/') {
            // permanently redirect paths with a trailing slash
            // to their non-trailing counterpart
            $uri = $uri->withPath(substr($path, 0, -1));

            if ($request->getMethod() === 'GET') {
                return $response->withRedirect((string)$uri, 301);
            }

            return $next($request->withUri($uri), $response);
        }

        return $next($request, $response);
    }
}