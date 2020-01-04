<?php
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;
use Cake\Core\Configure;

Router::plugin('MakvilleStorage', ['path' => Configure::read('makville-storage-path', '/makville-storage')], function (RouteBuilder $routes) {
    $routes->extensions(['json', 'xml']);
    $routes->fallbacks(DashedRoute::class);
});
