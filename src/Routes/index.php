<?php
use Test\Controllers\HomeController;
use Test\Controllers\LoginController;
use Test\Controllers\AccountController;
use Test\Controllers\MemberController;
use Test\Controllers\ApplicationController;
use Test\Controllers\PlayerController;
use Test\Controllers\TrainingController;
use Test\Controllers\SquadController;
use Test\Controllers\MatchController;

use Test\Controllers\Error;
use Test\Controllers\Auth;

use Test\Router;

$router = new Router();

// HTTP RESPONSE STATUS CONTROLLER
$router->get('/index', Error::class, 'error');

// INDEX
$router->get('/', HomeController::class, 'index');
$router->get('/dashboard', HomeController::class, 'dashboard');

// LOGIN AND LOGOUT
$router->get('/login', LoginController::class, 'login');
$router->post('/login', LoginController::class, 'login');

$router->get('/logout', LoginController::class, 'logout');
$router->post('/logout', LoginController::class, 'logout');


// ACCOUNT REGISTRATION
$router->get('/members/create-login', MemberController::class, 'createLogin');
$router->post('/members/create-login', MemberController::class, 'createLogin');

$router->get('/account/reset-password', AccountController::class, 'resetPassword');
$router->post('/account/reset-password', AccountController::class, 'resetPassword');

$router->get('/register/member', MemberController::class, 'registerMember');
$router->post('/register/member', MemberController::class, 'registerMember');

// MEMBER CONTROLLER
$router->get('/members', MemberController::class, 'index');
$router->post('/members', MemberController::class, 'index');

$router->get('/members/no-login', MemberController::class, 'membersNoLogin');
$router->post('/members/no-login', MemberController::class, 'membersNoLogin');

// APPLICATION CONTROLLER
$router->get('/register', ApplicationController::class, 'index');
$router->post('/register', ApplicationController::class, 'index');

$router->get('/player-applications', ApplicationController::class, 'playerApplications');
$router->post('/player-applications', ApplicationController::class, 'playerApplications');

$router->get('/player-applications/application-details', ApplicationController::class, 'applicationDetails');
$router->post('/player-applications/application-details', ApplicationController::class, 'applicationDetails');

// SQUAD CONTROLLER
$router->get('/squad', SquadController::class, 'index');
$router->post('/squad', SquadController::class, 'index');

// TRAINING CONTROLLER
$router->get('/training', TrainingController::class, 'index', ['view_training_session']);
$router->post('/training', TrainingController::class, 'index', ['view_training_session']);

$router->get('/training/create', TrainingController::class, 'create');
$router->post('/training/create', TrainingController::class, 'create');

$router->get('/training/record_attendance', TrainingController::class, 'record');
$router->post('/training/record_attendance', TrainingController::class, 'record');

$router->get('/training/view', TrainingController::class, 'view');
$router->post('/training/view', TrainingController::class, 'view');

$router->get('/training/update', TrainingController::class, 'update');
$router->post('/training/update', TrainingController::class, 'update');


$router->get('/player', PlayerController::class, 'displayPlayer');

// MATCH CONTROLLER
$router->get('/match', MatchController::class, 'index');
$router->get('/match/junior', MatchController::class, 'juniorMatch', ['view_junior_match']);
$router->get('/match/senior', MatchController::class, 'seniorMatch');

$router->get('/match/create', MatchController::class, 'create', ['create_match']);
$router->post('/match/create', MatchController::class, 'create', ['create_match']);


$router->dispatch();


?>