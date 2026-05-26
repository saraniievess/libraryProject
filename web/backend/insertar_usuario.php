<?php

declare(strict_types=1);

session_start();

use \app\config_factory;
use \app\pdo_factory;
use \resena\database\user_repository;
use \resena\model\user;
use \session\session_manager;

require_once("../src/autoload.php");

$config_factory = new config_factory();
$pdo_factory = new pdo_factory();
$database_connection = $pdo_factory->create($config_factory->create_production());

$session_id = session_id();
if ($session_id === false) {
    die('No se pudo obtener la sesión');
}
$session_manager = new session_manager($database_connection, $session_id);
$session_manager->commit_last_activity();
$current_user = $session_manager->get_logged_in_user();

// Login
if ($current_user === null) {
    header('Location: ../index.php');
    exit(0);
}

// Admin
if ($current_user->get_role() !== 'admin') {
    die("No tienes permisos para esto");
}

if (
    $_SERVER['REQUEST_METHOD'] !== 'POST'
    || !isset($_POST['nombre'])
) {
    header('Location: ../introducir_usuarios.php');
    exit(0);
}

$user_repository = new user_repository($database_connection);
$nombre = trim($_POST['nombre']);
$birthdate = new \DateTime($_POST['fecha']);
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$role = trim($_POST['role']);

try {
    $user = new user($nombre, $birthdate, $password, $role);
    $user_repository->insert($user);
    header("Location: ../introducir_usuarios.php?origin=new_user&insertion=ok");
} catch (\Throwable $e) {
    error_log("something failed when inserting the user: {$e->getMessage()}");
    header("Location: ../introducir_usuarios.php?origin=new_user&insertion=error");
}

exit(0);
