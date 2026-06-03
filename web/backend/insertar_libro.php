<?php

declare(strict_types=1);

session_start();

use \app\config_factory;
use \app\pdo_factory;
use \catalogo\database\book_repository;
use \catalogo\model\book;
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

// Admin
if ($current_user->get_role() !== 'admin') {
    header("Content-Type: application/json");
    echo json_encode(["status" => "error", "message" => "No tienes permisos para esto"]);
    exit(0);
}

if (!isset($_PATCH['titulo'])) {
    header("Content-Type: application/json");
    echo json_encode(["status" => "error"]);
    exit(0);
}

$book_repository = new book_repository($database_connection);

if (!is_string($_PATCH['titulo'])) {
    exit(0);
}
$titulo = trim($_PATCH['titulo']);
if (!is_string($_PATCH['autor'])) {
    exit(0);
}
$autor = trim($_PATCH['autor']);
if (!is_string($_PATCH['editorial'])) {
    exit(0);
}
$editorial = trim($_PATCH['editorial']);
if (!is_string($_PATCH['genero'])) {
    exit(0);
}
$genero = trim($_PATCH['genero']);
$pag_total = (int) $_PATCH['pag_total'];

header("Content-Type: application/json");

try {
    $book = new book($titulo, $autor, $editorial, $genero, $pag_total);
    $book_repository->insert($book);
    echo json_encode(["status" => "ok"]);
} catch (\Throwable $e) {
    error_log("something failed when inserting the book: {$e->getMessage()}");
    header("Content-Type: application/json");
    echo json_encode(["status" => "error"]);
}

exit(0);
