<?php

declare(strict_types=1);

session_start();

use \app\service_provider;
use \resena\database\review_repository;
use \view\html_view;
use \view\review_list_title;

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

$title = $_GET['title'] ?? '';

$review_repository = new review_repository($database_connection);

$reviews = $review_repository->findReviewByTitle($title);

$review_list_title = new review_list_title($title);
$view = new html_view();
echo $view->create($review_list_title);

exit(0);
