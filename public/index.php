<?php

if ( !session_id() ) session_start();

require __DIR__ . '/../vendor/autoload.php';



$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/', [ 'Controllers\HomeController', 'homePage']);
    $r->addRoute('GET', '/add', [ 'Controllers\HomeController', 'addUser']);
    $r->addRoute('GET', '/statistic', [ 'Controllers\HomeController', 'statistic']);
    $r->addRoute('POST', '/create', [ 'Controllers\HomeController', 'create']);
    // {id} must be a number (\d+)
    $r->addRoute('GET', '/delete/{id:\d+}', [ 'Controllers\HomeController', 'deleteUser']);
    // The /{title} suffix is optional
    $r->addRoute('GET', '/articles/{id:\d+}[/{title}]', 'get_article_handler');
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        echo 404;
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        echo 405;
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        call_user_func([$handler[0],$handler[1]], $vars);
        break;
}



