<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'LogPortiqueController::index', ['as' => 'collaborateurs']);
$routes->get('/logs', 'LogPortiqueController::logs', ['as' => 'logs']);
