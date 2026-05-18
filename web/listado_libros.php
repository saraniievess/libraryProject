<?php

declare(strict_types=1);

use \app\config_factory;
use \app\pdo_factory;
use \catalogo\model\book;
use \catalogo\database\book_repository;
use \view\html_view;
use \view\book_list_view;

require_once("src/autoload.php");
require_once("auth.php");

require_login();

//Setup
$config_factory = new config_factory();
$pdo_factory = new pdo_factory();
$database_connection = $pdo_factory->create($config_factory->create_production());

//Setup data.
$book_repo = new book_repository($database_connection);
$books = $book_repo->getAllBooks();

//Setup view.
$book_list_view = new book_list_view($books);
$view = new html_view();
echo $view->create($book_list_view);
exit(0);
