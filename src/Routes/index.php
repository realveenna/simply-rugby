<?php
use Test\Controllers\HomeController;
use Test\Controllers\LoginController;
use Test\Controllers\AccountController;


use Test\Router;

$router = new Router();

$router->get('/', HomeController::class, 'index');
$router->get('/dashboard', HomeController::class, 'dashboard');

$router->get('/login', LoginController::class, 'login');
$router->post('/login', LoginController::class, 'login');

$router->get('/register', AccountController::class, 'register');
$router->post('/register', AccountController::class, 'register');


$router->get('/createAccount', AccountController::class, 'createAccount');
$router->post('/createAccount', AccountController::class, 'createAccount');

$router->get('/logout', AccountController::class, 'logout');


$router->dispatch();

?>