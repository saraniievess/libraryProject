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

if ($session_manager->is_visitor()) {
    die("No tienes permisos para esto");
}

// Login
if ($current_user === null) {
    header('Location: ../index.php');
    exit(0);
}

if (
    $_SERVER['REQUEST_METHOD'] !== 'POST'
    || !isset($_POST['titulo'])
) {
    header('Location: ../introducir_resenas.php');
    exit(0);
}

$review_repository = new review_repository($database_connection);

$titulo = trim($_POST['titulo']);
$usuario = trim($_POST['usuario']);
$fecha = new \DateTime($_POST['fecha']);
$ranking = (int) $_POST['ranking'];
$info = trim($_POST['info']);

try {
    $review = new review($titulo, $usuario, $ranking, $fecha, $info);
    $review_repository->insert($review);
    header("Location: ../introducir_resenas.php?insertion=ok");
} catch (\Throwable $e) {
    error_log("something failed when inserting the review: {$e->getMessage()}");
    header("Location: ../introducir_resenas.php?insertion=error");
}

exit(0);
