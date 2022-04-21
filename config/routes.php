<?php
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

Router::plugin(
    'ContentBlocks',
    ['path' => '/content-blocks'],
    function (RouteBuilder $routes) {
        $routes->setRouteClass(DashedRoute::class);
        $routes->fallbacks(DashedRoute::class);
    }
);

Router::prefix(
    "admin",
    function (RouteBuilder $routes) {
        $routes->plugin(
            "ContentBlocks",
            ["path" => "/content-blocks"],
            function (RouteBuilder $routes) {
                $routes->setRouteClass(DashedRoute::class);
                $routes->fallbacks(DashedRoute::class);
            }
        );
        $routes->fallbacks(DashedRoute::class);
    }
);
