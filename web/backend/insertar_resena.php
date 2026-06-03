<?php

declare(strict_types=1);

session_start();

ini_set("display_errors", "1");
ini_set("error_reporting", "-1");

use \app\config_factory;
use \app\pdo_factory;
use \resena\database\review_repository;
use \resena\model\review;
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
    echo json_encode(["status" => "ok"]);
} catch (\Throwable $e) {
    error_log("something failed when inserting the review: {$e->getMessage()}");
    header("Content-Type: application/json");
    echo json_encode(["status" => "error"]);
}

exit(0);
