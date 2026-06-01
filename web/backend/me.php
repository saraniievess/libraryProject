<?php

declare(strict_types=1);

session_start();

use \app\config_factory;
use \app\pdo_factory;
use \session\session_manager;

require_once("../src/autoload.php");

header("Content-Type: application/json");

$config_factory = new config_factory();
$pdo_factory = new pdo_factory();

$database_connection = $pdo_factory->create($config_factory->create_production());
$session_id = session_id();

if ($session_id === false) {
    echo json_encode([
        "logged_in" => false,
        "role" => null,
        "user_id" => null
    ]);
    exit(0);
}

$session_manager = new session_manager($database_connection, $session_id);
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
