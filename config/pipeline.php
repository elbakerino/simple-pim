<?php

return static function(
    \Satellite\Response\ResponsePipe  $pipe,
    \Satellite\KernelRoute\Router     $router,
    \Psr\Container\ContainerInterface $container
): void {
    $pipe->with((new Middlewares\JsonPayload())
        ->associative(false)
        ->depth(64));
    $pipe->with(new Middlewares\UrlEncodePayload());

    $pipe->with(new App\Middlewares\CorsMiddleware(
        [
            'http://localhost:3335',
            'https://dynamodb-visualizer.bemit.codes',
        ],
        [
            'Content-Type',
            'Accept',
            'AUTHORIZATION',
            'AUDIENCE',
            'X-Requested-With',
            'X_AUTH_TOKEN',
            'X_AUTH_SIGNATURE',
            'X_API_OPTION',
            'remember-me',
        ],
        [
            'Content-Range',
        ],
        7200,
    ));

    $pipe->with(new Middlewares\FastRoute($router->buildRouter()));

    $pipe->with(new Middlewares\RequestHandler($container));
};
