<?php

declare(strict_types=1);

use \app\config_factory;
use \app\pdo_factory;
use \view\html_view;
use \view\form_libro;
use \catalogo\database\book_repository;
use \catalogo\model\book;

require_once("src/autoload.php");
require_once("auth.php");

require_login();

if (!is_admin()) {
    die('Acceso denegado');
}

$config_factory = new config_factory();
$pdo_factory = new pdo_factory();
$database_connection = $pdo_factory->create($config_factory->create_production());

$book_repository = new book_repository($database_connection);

$form_libro = new form_libro();
$view = new html_view();
echo $view->create($form_libro);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['titulo'])) {

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
exit(0);
