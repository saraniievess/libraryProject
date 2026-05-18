<?php

declare(strict_types=1);

use \app\config_factory;
use \app\pdo_factory;
use \resena\model\user;
use \resena\database\user_repository;
use \view\html_view;
use \view\user_list_view;

require_once("src/autoload.php");
require_once("auth.php");

require_login();

//Setup
$config_factory = new config_factory();
$pdo_factory = new pdo_factory();
$database_connection = $pdo_factory->create($config_factory->create_production());

//Setup data.
$user_repo = new user_repository($database_connection);
$users = $user_repo->getAllUsers();

//Setup view.
$user_list_view = new user_list_view($users);
$view = new html_view();
echo $view->create($user_list_view);
exit(0);
