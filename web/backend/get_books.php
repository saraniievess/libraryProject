<?php

declare(strict_types=1);

session_start();

use \app\config_factory;
use \app\pdo_factory;
use \catalogo\database\book_repository;

require_once("../src/autoload.php");
header("Content-Type: application/json");

$config_factory = new config_factory();
$pdo_factory = new pdo_factory();
$database_connection = $pdo_factory->create($config_factory->create_production());
$book_repository = new book_repository($database_connection);
$books = $book_repository->getAllBooks();
$data = [];

foreach ($books as $book) {
    $data[] = [
        "id" => $book->get_id(),
        "title" => $book->get_title(),
        "author" => $book->get_author(),
        "house" => $book->get_house(),
        "page_count" => $book->get_page_count(),
        "genre" => $book->get_genre()
    ];
}

$result = ["total" => count($data), "data" => $data];
echo json_encode($result);

exit(0);
