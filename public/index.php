<?php

require_once __DIR__ . '/../vendor/autoload.php';
use TanzilalGummilang\PHP\LoginManagement\App\Router;
use TanzilalGummilang\PHP\LoginManagement\Config\Database;
use TanzilalGummilang\PHP\LoginManagement\Controller\HomeController;
use TanzilalGummilang\PHP\LoginManagement\Controller\UserController;
use TanzilalGummilang\PHP\LoginManagement\Middleware\MustLoginMiddleware;
use TanzilalGummilang\PHP\LoginManagement\Middleware\MustNotLoginMiddleware;


Database::getConnection("prod");

// HomeController
Router::add('GET', '/', HomeController::class, 'index', []);

// UserController
Router::add('GET', '/users/register', UserController::class, 'register', [MustNotLoginMiddleware::class]);
Router::add('POST', '/users/register', UserController::class, 'postRegister', [MustNotLoginMiddleware::class]);
Router::add('GET', '/users/login', UserController::class, 'login', [MustNotLoginMiddleware::class]);
Router::add('POST', '/users/login', UserController::class, 'postLogin', [MustNotLoginMiddleware::class]);
Router::add('GET', '/users/logout', UserController::class, 'logout', [MustLoginMiddleware::class]);

Router::run();