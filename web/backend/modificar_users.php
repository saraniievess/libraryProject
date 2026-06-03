<?php

declare(strict_types=1);

session_start();

use \app\config_factory;
use \app\pdo_factory;
use \resena\database\user_repository;
use \resena\model\user;
use \session\session_manager;

require_once("../src/autoload.php");

if ($_SERVER['REQUEST_METHOD'] !== 'PATCH') {
    exit(0);
}

$input = file_get_contents("php://input");

if ($input === false) {
    exit(0);
}

parse_str($input, $_PATCH);

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
    $user->set_role($role);
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
    header("Content-Type: application/json");
    echo json_encode(["status" => "ok"]);
    exit(0);
}

header("Content-Type: application/json");
echo json_encode(["status" => "error"]);

exit(0);
