<?php

declare(strict_types=1);

session_start();

use \session\session_manager;
use \app\service_provider;

require_once("../src/autoload.php");

header("Content-Type: application/json");

$service_provider = new service_provider();
$session_manager = $service_provider->get_session_manager();
$current_user = $session_manager->get_logged_in_user();
$current_user = $session_manager->get_logged_in_user();

if ($current_user === null) {
    echo json_encode([
        "logged_in" => false,
        "role" => null,
        "user_id" => null
    ]);
    exit(0);
}

echo json_encode([
    "logged_in" => true,
    "role" => $current_user->get_role(),
    "user_id" => $current_user->get_id()
]);

exit(0);
