<?php

declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('error_reporting', '-1');

use \app\config_factory;
use \app\pdo_factory;
use \catalogo\database\book_repository;
use \resena\database\review_repository;
use \view\html_view;
use \view\review_list_title;

require_once("src/autoload.php");

$config_factory = new config_factory();
$pdo_factory = new pdo_factory();
$database_connection = $pdo_factory->create($config_factory->create_production());

if (!array_key_exists("book_id", $_GET)) {
	header("location: index.html");
	exit(0);
}

$book_id = $_GET["book_id"];
if (!ctype_digit($book_id)) {
	header("location: index.html");
	exit(0);
}

$book_repo = new book_repository($database_connection);
$book = $book_repo->findBookById((int)$book_id);

if (null === $book) {
	header("location: index.html");
	exit(0);
}

$review_repo = new review_repository($database_connection);
$reviews = $review_repo->findReviewByTitle($book->get_title());

$review_list_title = new review_list_title($reviews, $book->get_title());
$view = new html_view();
echo $view->create($review_list_title);
exit(0);
