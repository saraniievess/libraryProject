<?php

declare(strict_types=1);

session_start();

use \app\config_factory;
use \app\pdo_factory;
use \catalogo\database\book_repository;
use \catalogo\model\book;
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

// Login
if ($current_user === null) {
    header('Location: ../index.php');
    exit(0);
}

// Admin
if ($current_user->get_role() !== 'admin') {
    die("No tienes permisos para esto");
}

if (
    $_SERVER['REQUEST_METHOD'] !== 'POST'
    || !isset($_POST['titulo'])
) {
    header('Location: ../introducir_libros.php');
    exit(0);
}

$book_repository = new book_repository($database_connection);

$titulo = trim($_POST['titulo']);
$autor = trim($_POST['autor']);
$editorial = trim($_POST['editorial']);
$genero = trim($_POST['genero']);
$pag_total = (int) $_POST['pag_total'];

try {
    $book = new book($titulo, $autor, $editorial, $genero, $pag_total);
    $book_repository->insert($book);
    header("Location: ../introducir_libros.php?insertion=ok");
} catch (\Throwable $e) {
    error_log("something failed when inserting the book: {$e->getMessage()}");
    header("Location: ../introducir_libro.php?insertion=error");
}

exit(0);
