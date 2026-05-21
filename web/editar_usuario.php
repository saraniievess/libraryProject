<?php

declare(strict_types=1);

session_start();

use \app\config_factory;
use \app\pdo_factory;
use \resena\database\user_repository;
use \resena\model\user;
use \session\session_manager;
use \view\html_view;
use \view\form_users;


require_once("src/autoload.php");

$config_factory = new config_factory();
$pdo_factory = new pdo_factory();
$database_connection = $pdo_factory->create($config_factory->create_production());
$session_manager = new session_manager($database_connection, session_id());
$current_user = $session_manager->get_logged_in_user();
$session_manager->commit_last_activity();

// Login
if (null === $current_user) {
	header('Location: index.php');
	exit(0);
}

$user_repository = new user_repository($database_connection);

$user_id = (int)($_GET['user_id'] ?? 0);

$user = $user_repository->findUserById($user_id);

if (null === $user) {
	die("No existe ese usuario");
}

// ¿Puede editarlo?
$can_edit =
	$current_user->get_id() === $user->get_id()
	|| $current_user->get_role() === 'admin';
if (!$can_edit) {
	die("No puedes editar este usuario");
}

$form_users = new form_users($user, null);
$view = new html_view();
echo $view->create($form_users);

exit(0);
