<?php

declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('error_reporting', '-1');

use \app\config_factory;
use \app\pdo_factory;
use \resena\database\user_repository;
use \resena\database\review_repository;
use \view\html_view;
use \view\review_list_name;

require_once("src/autoload.php");

$config_factory = new config_factory();
$pdo_factory = new pdo_factory();
$database_connection = $pdo_factory->create($config_factory->create_production());

if (!array_key_exists("user_id", $_GET)) {
    header("location: index.html");
    exit(0);
}

$user_id = $_GET["user_id"];
if (!ctype_digit($user_id)) {
    header("location: index.html");
    exit(0);
}

$user_repo = new user_repository($database_connection);
$user = $user_repo->findUserById((int)$user_id);

if (null === $user) {
    header("location: index.html");
    exit(0);
}

$review_repo = new review_repository($database_connection);
$reviews = $review_repo->findReviewByName($user->get_name());

$review_list_name = new review_list_name($reviews, $user->get_name());
$view = new html_view();
echo $view->create($review_list_name);
exit(0);
