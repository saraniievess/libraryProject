<?php

declare(strict_types=1);

session_start();

use \app\config_factory;
use \app\pdo_factory;
use \resena\database\user_repository;
use \resena\model\user;
use \session\session_manager;

require_once("../src/autoload.php");

if ($_SERVER['REQUEST_METHOD'] === 'PATCH') {
    $input = file_get_contents("php://input");

    if ($input === false) {
        exit(0);
    }

    parse_str($input, $_PATCH);
}

$config_factory = new config_factory();
$pdo_factory = new pdo_factory();
$database_connection = $pdo_factory->create($config_factory->create_production());

$session_id = session_id();
if ($session_id === false) {
    header("Content-Type: application/json");
    echo json_encode(["status" => "error", "message" => "No se pudo obtener la sesión"]);
    exit(0);
}
$session_manager = new session_manager($database_connection, $session_id);
$session_manager->commit_last_activity();
$current_user = $session_manager->get_logged_in_user();

// Login
if ($current_user === null) {
    header("Content-Type: application/json");
    echo json_encode(["status" => "error", "message" => "No has iniciado sesión"]);
    exit(0);
}

// Admin
if ($current_user->get_role() !== 'admin') {
    header("Content-Type: application/json");
    echo json_encode(["status" => "error", "message" => "No tienes permisos para esto"]);
    exit(0);
}

if (!isset($_PATCH['nombre'])) {
    header("Content-Type: application/json");
    echo json_encode(["status" => "error"]);
    exit(0);
}

$user_repository = new user_repository($database_connection);
if (!is_string($_PATCH['nombre'])) {
    exit(0);
}
$nombre = trim($_PATCH['nombre']);
$birthdate = new \DateTime($_PATCH['fecha']);
$password = password_hash($_PATCH['password'], PASSWORD_DEFAULT);
if (!is_string($_PATCH['role'])) {
    exit(0);
}
$role = trim($_PATCH['role']);

header("Content-Type: application/json");

try {
    $user = new user($nombre, $birthdate, $password, $role);
    $user_repository->insert($user);
    echo json_encode(["status" => "ok"]);
} catch (\Throwable $e) {
    error_log("something failed when inserting the user: {$e->getMessage()}");
    header("Content-Type: application/json");
    echo json_encode(["status" => "error"]);
}

exit(0);
