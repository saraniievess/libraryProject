<?php

declare(strict_types=1);

session_start();

use \app\service_provider;
use \catalogo\database\book_repository;
use \view\html_view;
use \view\book_list_view;

require_once("src/autoload.php");

$service_provider = new service_provider();
$database_connection = $service_provider->get_database_connection();
$session_manager = $service_provider->get_session_manager();
$current_user = $session_manager->get_logged_in_user();
$session_manager->commit_last_activity();

// Login
if (
    null === $current_user
    && !$session_manager->is_visitor()
) {
    header('Location: index.php');
    exit(0);
}

$book_repository = new book_repository($database_connection);
$books = $book_repository->getAllBooks();

$book_list_view = new book_list_view();
$view = new html_view();
echo $view->create($book_list_view);

exit(0);
