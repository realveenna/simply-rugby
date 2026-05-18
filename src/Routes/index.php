<?php
use Test\Controllers\HomeController;
use Test\Controllers\LoginController;
use Test\Controllers\AccountController;
use Test\Controllers\MemberController;
use Test\Controllers\ApplicationController;
use Test\Controllers\PlayerController;
use Test\Controllers\TrainingController;
use Test\Controllers\SquadController;

use Test\Controllers\Error;
use Test\Controllers\Auth;

use Test\Router;

$router = new Router();


$router->get('/403', Error::class, 'forbidden');
$router->get('/404', Error::class, 'notFound');

$router->get('/', HomeController::class, 'index');
$router->get('/dashboard', HomeController::class, 'dashboard');

$router->get('/login', LoginController::class, 'login');
$router->post('/login', LoginController::class, 'login');

$router->get('/logout', LoginController::class, 'logout');
$router->post('/logout', LoginController::class, 'logout');

// $router->get('/register', AccountController::class, 'register');
// $router->post('/register', AccountController::class, 'register');

$router->get('/register', ApplicationController::class, 'index');
$router->post('/register', ApplicationController::class, 'index');

$router->get('/members/create-login', MemberController::class, 'createLogin');
$router->post('/members/create-login', MemberController::class, 'createLogin');

$router->get('/account/reset-password', AccountController::class, 'resetPassword');
$router->post('/account/reset-password', AccountController::class, 'resetPassword');

$router->get('/register/member', MemberController::class, 'registerMember');
$router->post('/register/member', MemberController::class, 'registerMember');

$router->get('/members', MemberController::class, 'index');
$router->post('/members', MemberController::class, 'index');

$router->get('/members/no-login', MemberController::class, 'membersNoLogin');
$router->post('/members/no-login', MemberController::class, 'membersNoLogin');

$router->get('/player-applications', ApplicationController::class, 'playerApplications');
$router->post('/player-applications', ApplicationController::class, 'playerApplications');

$router->get('/player-applications/application-details', ApplicationController::class, 'applicationDetails');
$router->post('/player-applications/application-details', ApplicationController::class, 'applicationDetails');

$router->get('/squad', SquadController::class, 'index');
$router->post('/squad', SquadController::class, 'index');

$router->get('/training', TrainingController::class, 'index');
$router->post('/training', TrainingController::class, 'index');

$router->get('/training/create', TrainingController::class, 'create');
$router->post('/training/create', TrainingController::class, 'create');

// $router->get('/players', PlayerController::class, 'index');
// $router->post('/players', PlayerController::class, 'index');

$router->get('/player', PlayerController::class, 'displayPlayer');


$router->dispatch();


?>