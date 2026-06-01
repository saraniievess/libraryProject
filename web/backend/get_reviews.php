<?php

declare(strict_types=1);

session_start();

use \app\config_factory;
use \app\pdo_factory;
use \resena\database\review_repository;

require_once("../src/autoload.php");

header("Content-Type: application/json");

$title = $_GET["title"] ?? "";
$config_factory = new config_factory();
$pdo_factory = new pdo_factory();
$database_connection = $pdo_factory->create($config_factory->create_production());
$review_repository = new review_repository($database_connection);
$reviews = $review_repository->findReviewByTitle($title);
$data = [];

foreach ($reviews as $review) {
    $data[] = [
        "id" => $review->get_id(),
        "user" => $review->get_user(),
        "ranking" => $review->get_ranking(),
        "date" =>
        $review
            ->get_finished_date()
            ->format("d-m-Y"),
        "info" => $review->get_info()
    ];
}

$result = ["total" => count($data), "data" => $data];
echo json_encode($result);

exit(0);
