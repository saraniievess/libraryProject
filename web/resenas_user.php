<?php

declare(strict_types=1);

session_start();

use \app\config_factory;
use \app\pdo_factory;
use \resena\database\review_repository;
use \view\html_view;
use \view\review_list_name;
use \session\session_manager;

require_once("src/autoload.php");

$config_factory = new config_factory();
$pdo_factory = new pdo_factory();
$database_connection = $pdo_factory->create($config_factory->create_production());

$session_id = session_id();
if ($session_id === false) {
    die('No se pudo obtener la sesión');
}
$session_manager = new session_manager($database_connection, $session_id);
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

$username = $_GET['username'] ?? '';

$review_repository = new review_repository($database_connection);

$reviews = $review_repository->findReviewByName($username);

$review_list_name = new review_list_name($reviews, $username);
$view = new html_view();
echo $view->create($review_list_name);

exit(0);
