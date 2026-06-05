<?php

declare(strict_types=1);

session_start();

use \resena\database\review_repository;
use \app\service_provider;

require_once("../src/autoload.php");

header("Content-Type: application/json");

$title = $_GET["title"] ?? "";
$service_provider = new service_provider();
$database_connection = $service_provider->get_database_connection();
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
