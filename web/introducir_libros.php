<?php

declare(strict_types=1);

session_start();

use \app\config_factory;
use \app\pdo_factory;
use \view\html_view;
use \view\form_libro;
use \catalogo\database\book_repository;
use \catalogo\model\book;
use \session\session_manager;

require_once("src/autoload.php");

$config_factory = new config_factory();
$pdo_factory = new pdo_factory();
$database_connection = $pdo_factory->create($config_factory->create_production());
$session_manager = new session_manager($database_connection, session_id());
$current_user = $session_manager->get_logged_in_user();
$session_manager->commit_last_activity();

//Login
if (null === $current_user) {
    header('Location: index.php');
    exit(0);
}

//Admin
if ($current_user->get_role() !== 'admin') {
    die("No tienes permisos para esto");
}

$book_repository = new book_repository($database_connection);

if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['titulo'])
) {
    $titulo = trim($_POST['titulo']);
    $autor = trim($_POST['autor']);
    $editorial = trim($_POST['editorial']);
    $genero = trim($_POST['genero']);
    $pag_total = (int)$_POST['pag_total'];
    $book = new book(
        $autor,
        $titulo,
        $editorial,
        $genero,
        $pag_total
    );
    $book_repository->insert($book);
    echo "Libro añadido correctamente";
}

$form_libro = new form_libro();
$view = new html_view();
echo $view->create($form_libro);

exit(0);
