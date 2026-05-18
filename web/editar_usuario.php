<?php

declare(strict_types=1);

ini_set("display_errors", 1);
ini_set("error_reporting", -1);

use \app\config_factory;
use \app\pdo_factory;
use \view\html_view;
use \view\form_users;
use \resena\database\user_repository;
use \resena\model\user;

require_once("src/autoload.php");
require_once("auth.php");

require_login();

$user_id = $_POST["user_id"] ?? 0;

$config_factory = new config_factory();
$pdo_factory = new pdo_factory();
$database_connection = $pdo_factory->create($config_factory->create_production());

$user_repository = new user_repository($database_connection);
$user = $user_repository->findUserById((int)$user_id);
if (null === $user) {
	die("No existe ese usuario");
}

if (!can_edit_user($user->get_name())) {
	die('No puedes editar este usuario');
}

$form_users = new form_users($user, null);
$view = new html_view();
echo $view->create($form_users);

exit(0);
