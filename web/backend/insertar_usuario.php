<?php

declare(strict_types=1);

session_start();

use \resena\database\user_repository;
use \resena\model\user;
use \app\service_provider;

require_once("../src/autoload.php");

if ($_SERVER['REQUEST_METHOD'] !== 'PATCH') {
    exit(0);
}

$input = file_get_contents("php://input");

if ($input === false) {
    exit(0);
}

parse_str($input, $_PATCH);

$service_provider = new service_provider();
$database_connection = $service_provider->get_database_connection();
$session_manager = $service_provider->get_session_manager();
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
if (!is_string($_PATCH['fecha'])) {
    exit(0);
}
if (!is_string($_PATCH['password'])) {
    exit(0);
}
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

$service_provider->get_logger()->info($current_user->get_name() . " ha creado al usuario " . $nombre);

header("Content-Type: application/json");

try {
    $user = new user($nombre, $birthdate, $password, $role);
    $user_repository->insert($user);
    echo json_encode(["status" => "ok"]);
} catch (\Throwable $e) {
    $service_provider->get_logger()->error("Error insertando usuario: {$e->getMessage()}");
    header("Content-Type: application/json");
    echo json_encode(["status" => "error"]);
}

exit(0);
