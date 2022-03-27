<?php

require_once __DIR__ . '/../vendor/autoload.php';
use TanzilalGummilang\PHP\LoginManagement\App\Router;
use TanzilalGummilang\PHP\LoginManagement\Controller\HomeController;


Router::add('GET', '/', HomeController::class, 'index', []);

Router::run();