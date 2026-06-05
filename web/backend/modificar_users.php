<?php

declare(strict_types=1);

session_start();

use \resena\database\user_repository;
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

$user_repository = new user_repository($database_connection);
$user_id = (int)($_PATCH['user_id'] ?? 0);
$user = $user_repository->findUserById($user_id);

if (null === $user) {
    header("Content-Type: application/json");
    echo json_encode(["status" => "error", "message" => "No existe ese usuario"]);
    exit(0);
}

// ¿Puede editarlo?
$can_edit =
    $current_user->get_id() === $user->get_id()
    || $current_user->get_role() === 'admin';
if (!$can_edit) {
    $service_provider->get_logger()->warning("Intento de modificado sin permisos");
    header("Content-Type: application/json");
    echo json_encode(["status" => "error", "message" => "No puedes editar este usuario"]);
    exit(0);
}

if (
    isset($_PATCH['nombre'])
    && isset($_PATCH['fecha'])
    && isset($_PATCH['role'])
) {
    if (!is_string($_PATCH['nombre'])) {
        exit(0);
    }
    $name = trim($_PATCH['nombre']);
    if (!is_string($_PATCH['fecha'])) {
        exit(0);
    }
    $birthdate = new \DateTime($_PATCH['fecha']);
    if (!is_string($_PATCH['role'])) {
        exit(0);
    }
    $role = trim($_PATCH['role']);
    $user->set_name($name);
    $user->set_birthdate($birthdate);
    if ($role !== "") {
        $user->set_role($role);
    }
    if (
        isset($_PATCH['password'])
        && is_string($_PATCH['password'])
        && trim($_PATCH['password']) !== ''
    ) {
        $hashed_password = password_hash(
            $_PATCH['password'],
            PASSWORD_DEFAULT
        );
        $user->set_password(
            $hashed_password
        );
    }
    $user_repository->update($user);
    $service_provider->get_logger()->info($current_user->get_name() . " ha modificado al usuario " . $user->get_name());
    header("Content-Type: application/json");
    echo json_encode(["status" => "ok"]);
    exit(0);
}

header("Content-Type: application/json");
echo json_encode(["status" => "error"]);

exit(0);
