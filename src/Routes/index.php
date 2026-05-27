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
use Test\Controllers\InjuryController;
use Test\Controllers\MailController;

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

$router->get('/account', AccountController::class, 'index');
$router->post('/account', AccountController::class, 'index');

$router->get('/register/member', MemberController::class, 'registerMember');
$router->post('/register/member', MemberController::class, 'registerMember');

$router->get('/account/update', AccountController::class, 'update');
$router->post('/account/update', AccountController::class, 'update');

// MEMBER CONTROLLER
$router->get('/members', MemberController::class, 'index',['view_member']);
$router->post('/members', MemberController::class, 'index',['view_member']);

$router->get('/members/no-login', MemberController::class, 'membersNoLogin',['view_member']);
$router->post('/members/no-login', MemberController::class, 'membersNoLogin',['view_member']);

$router->get('/members/view', MemberController::class, 'view',['view_member']);
$router->post('/members/view', MemberController::class, 'view',['view_member']);

$router->get('/members/update', MemberController::class, 'update');
$router->post('/members/update', MemberController::class, 'update');

$router->get('/members/renewal', MemberController::class, 'renewal');
$router->post('/members/renewal', MemberController::class, 'renewal');

// APPLICATION CONTROLLER
$router->get('/register', ApplicationController::class, 'index');
$router->post('/register', ApplicationController::class, 'index');

$router->get('/player-applications', ApplicationController::class, 'playerApplications',['manage_player_application']);
$router->post('/player-applications', ApplicationController::class, 'playerApplications',['manage_player_application']);

$router->get('/player-applications/application-details', ApplicationController::class, 'applicationDetails',['manage_player_application']);
$router->post('/player-applications/application-details', ApplicationController::class, 'applicationDetails',['manage_player_application']);

// SQUAD CONTROLLER
$router->get('/squad', SquadController::class, 'index',['view_squad']);
$router->post('/squad', SquadController::class, 'index',['view_squad']);

$router->get('/squad/senior', SquadController::class, 'senior');
$router->post('/squad/senior', SquadController::class, 'senior');

// TRAINING CONTROLLER
$router->get('/training', TrainingController::class, 'index', ['view_training_session']);
$router->post('/training', TrainingController::class, 'index', ['view_training_session']);

$router->get('/training/create', TrainingController::class, 'create');
$router->post('/training/create', TrainingController::class, 'create');

$router->get('/training/record_attendance', TrainingController::class, 'record',['record_attendance']);
$router->post('/training/record_attendance', TrainingController::class, 'record',['record_attendance']);

$router->get('/training/record_player_skills', TrainingController::class, 'recordSkill', ['record_player_skills']);
$router->post('/training/record_player_skills', TrainingController::class, 'recordSkill', ['record_player_skills']);

$router->get('/training/view', TrainingController::class, 'view', ['view_training_session']);
$router->post('/training/view', TrainingController::class, 'view', ['view_training_session']);

$router->get('/training/update', TrainingController::class, 'update', ['update_training_session']);
$router->post('/training/update', TrainingController::class, 'update', ['update_training_session']);

// PLAYER INFORMATION
$router->get('/player', PlayerController::class, 'displayPlayer', ['view_player_details']);

// MATCH CONTROLLER
$router->get('/match', MatchController::class, 'index');
$router->get('/match/junior', MatchController::class, 'juniorMatch', ['view_junior_match']);
$router->get('/match/senior', MatchController::class, 'seniorMatch');

$router->get('/match/create', MatchController::class, 'create', ['create_match']);
$router->post('/match/create', MatchController::class, 'create', ['create_match']);

$router->get('/match/view', MatchController::class, 'view');
$router->post('/match/view', MatchController::class, 'view');

$router->get('/match/lineup', MatchController::class, 'lineup', ['create_team']);
$router->post('/match/lineup', MatchController::class, 'lineup', ['create_team']);

$router->get('/match/update', MatchController::class, 'updateMatch', ['update_match']);
$router->post('/match/update', MatchController::class, 'updateMatch', ['update_match']);

$router->get('/match/update-result', MatchController::class, 'updateResult', ['create_match']);
$router->post('/match/update-result', MatchController::class, 'updateResult', ['create_match']);

$router->get('/match/all', MatchController::class, 'all', ['view_match']);
$router->post('/match/all', MatchController::class, 'all', ['view_match']);

$router->get('/match/match-player-stats', MatchController::class, 'matchPlayerStats', ['update_match','create_team']);
$router->post('/match/match-player-stats', MatchController::class, 'matchPlayerStats', ['update_match','create_team']);

// INJURY
$router->get('/injury', InjuryController::class,'index',['record_injury']);
$router->post('/injury', InjuryController::class,'index',['record_injury']);


// MAIL
$router->get('/mail', MailController::class,'sendMail',['send_messages']);
$router->post('/mail', MailController::class,'sendMail',['send_messages']);



$router->dispatch();


?>