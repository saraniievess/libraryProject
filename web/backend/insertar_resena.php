<?php

declare(strict_types=1);

session_start();

ini_set("display_errors", "1");
ini_set("error_reporting", "-1");

use \resena\database\review_repository;
use \resena\model\review;
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

if ($session_manager->is_visitor()) {
    header("Content-Type: application/json");
    echo json_encode(["status" => "error", "message" => "No tienes permisos para esto"]);
    exit(0);
}

// Login
if ($current_user === null) {
    header("Content-Type: application/json");
    echo json_encode(["status" => "error", "message" => "No has iniciado sesión"]);
    exit(0);
}

if (!isset($_PATCH['titulo'])) {
    header("Content-Type: application/json");
    echo json_encode(["status" => "error"]);
    exit(0);
}

$review_repository = new review_repository($database_connection);

if (!is_string($_PATCH['titulo'])) {
    exit(0);
}
$titulo = trim($_PATCH['titulo']);
if (!is_string($_PATCH['usuario'])) {
    exit(0);
}
$usuario = trim($_PATCH['usuario']);
if (!is_string($_PATCH['fecha'])) {
    exit(0);
}
$fecha = new \DateTime($_PATCH['fecha']);
$ranking = (int) $_PATCH['ranking'];
if (!is_string($_PATCH['info'])) {
    exit(0);
}
$info = trim($_PATCH['info']);

header("Content-Type: application/json");

try {
    $review = new review($titulo, $usuario, $ranking, $fecha, $info);
    $review_repository->insert($review);
    $service_provider->get_logger()->info($current_user->get_name() . " ha añadido una reseña del libro " . $titulo . " de " . $usuario);
    echo json_encode(["status" => "ok"]);
} catch (\Throwable $e) {
    $service_provider->get_logger()->error("Error insertando reseña: {$e->getMessage()}");
    header("Content-Type: application/json");
    echo json_encode(["status" => "error"]);
}

exit(0);
