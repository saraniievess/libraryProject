<?php

declare(strict_types=1);

session_start();

use \app\config_factory;
use \app\pdo_factory;
use \resena\database\user_repository;

require_once("../src/autoload.php");

header("Content-Type: application/json");

$config_factory = new config_factory();
$pdo_factory = new pdo_factory();
$database_connection = $pdo_factory->create($config_factory->create_production());
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
