<?php

declare(strict_types=1);

session_start();

use \app\service_provider;
use \resena\database\user_repository;
use \view\html_view;
use \view\form_users;

require_once("src/autoload.php");

$service_provider = new service_provider();
$database_connection = $service_provider->get_database_connection();
$session_manager = $service_provider->get_session_manager();
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

//The presence or absence of "origin" in the query string...
$origin = form_users::origin_list_user;
if (array_key_exists("origin", $_GET)) {
	//...may cause the return path to be different.
	switch ($_GET["origin"]) {
		case form_users::origin_new_user:
			$origin = $_GET["origin"];
			break;
	}
}

$form_users = new form_users($user, $origin, null);
$view = new html_view();
echo $view->create($form_users);

exit(0);
