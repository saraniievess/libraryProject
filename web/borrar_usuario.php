<?php

declare(strict_types=1);

session_start();

use \app\config_factory;
use \app\pdo_factory;
use \resena\database\user_repository;
use \session\session_manager;

require_once("src/autoload.php");

$config_factory = new config_factory();
$pdo_factory = new pdo_factory();
$database_connection = $pdo_factory->create($config_factory->create_production());
$session_manager = new session_manager($database_connection, session_id());
$current_user = $session_manager->get_logged_in_user();
$session_manager->commit_last_activity();

// Admin
if (
    null === $current_user
    || $current_user->get_role() !== 'admin'
) {
    die("No tienes permisos");
}

$user_id = (int)($_GET['user_id'] ?? 0);

$user_repository = new user_repository($database_connection);

$user = $user_repository->findUserById($user_id);

if (null === $user) {
    die("Usuario no encontrado");
}

$user_repository->delete($user);
header('Location: listado_users.php');

exit(0);
