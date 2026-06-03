<?php

declare(strict_types=1);

namespace backend;

session_start();

use \app\config_factory;
use \app\pdo_factory;
use \resena\database\user_repository;
use \session\session_manager;

require_once("../src/autoload.php");
header("Content-Type: application/json");

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


// Admin
if (
    null === $current_user
    || $current_user->get_role() !== 'admin'
) {
    header("Content-Type: application/json");
    echo json_encode(["status" => "error", "message" => "No tienes permisos"]);
    exit(0);
}

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {

    echo json_encode([
        "status" => "error"
    ]);

    exit(0);
}

$user_id = (int)($_GET['user_id'] ?? 0);
$user_repository = new user_repository($database_connection);
$user = $user_repository->findUserById($user_id);

if (null === $user) {
    header("Content-Type: application/json");
    echo json_encode(["status" => "error", "message" => "Usuario no encontrado"]);
    exit(0);
}

$user_repository->delete($user);
header("Content-Type: application/json");

echo json_encode([
    "status" => "ok"
]);

exit(0);
