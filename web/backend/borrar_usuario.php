<?php

declare(strict_types=1);

namespace backend;

session_start();

use \resena\database\user_repository;
use \app\service_provider;

require_once("../src/autoload.php");

header("Content-Type: application/json");

$service_provider = new service_provider();
$database_connection = $service_provider->get_database_connection();
$session_manager = $service_provider->get_session_manager();
$session_manager->commit_last_activity();
$current_user = $session_manager->get_logged_in_user();

// Admin
if (
    null === $current_user
    || $current_user->get_role() !== 'admin'
) {
    $service_provider->get_logger()->warning("Intento de borrado sin permisos");
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
$service_provider->get_logger()->warning($current_user->get_name() . " ha eliminado al usuario " . $user->get_name());
header("Content-Type: application/json");

echo json_encode([
    "status" => "ok"
]);

exit(0);
