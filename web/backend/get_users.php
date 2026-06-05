<?php

declare(strict_types=1);

session_start();

use \resena\database\user_repository;
use \app\service_provider;

require_once("../src/autoload.php");

header("Content-Type: application/json");

$service_provider = new service_provider();
$database_connection = $service_provider->get_database_connection();
$user_repository = new user_repository($database_connection);
$users = $user_repository->getAllUsers();
$data = [];

foreach ($users as $user) {
    $data[] = [
        "id" => $user->get_id(),
        "name" => $user->get_name(),
        "birthdate" =>
        $user
            ->get_birthdate()
            ->format("d-m-Y"),
        "role" => $user->get_role()
    ];
}

$result = ["total" => count($data), "data" => $data];
echo json_encode($result);

exit(0);
