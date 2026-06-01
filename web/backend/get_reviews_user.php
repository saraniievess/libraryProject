<?php

declare(strict_types=1);

session_start();

use \app\config_factory;
use \app\pdo_factory;
use \resena\database\review_repository;

require_once("../src/autoload.php");

header("Content-Type: application/json");

$username = $_GET["username"] ?? "";
$config_factory = new config_factory();
$pdo_factory = new pdo_factory();
$database_connection = $pdo_factory->create($config_factory->create_production());
$review_repository = new review_repository($database_connection);
$reviews = $review_repository->findReviewByName($username);
$data = [];

foreach ($reviews as $review) {
    $data[] = [
        "id" => $review->get_id(),
        "title" => $review->get_title(),
        "date" =>
        $review
            ->get_finished_date()
            ->format("d-m-Y"),
        "ranking" => $review->get_ranking(),
        "info" => $review->get_info()
    ];
}

$result = ["total" => count($data), "data" => $data];
echo json_encode($result);

exit(0);
