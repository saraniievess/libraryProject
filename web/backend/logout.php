<?php

declare(strict_types=1);

session_start();

use \session\session_manager;

require_once("../src/autoload.php");

$service_provider = new \app\service_provider();
$session_manager = $service_provider->get_session_manager();

$current_user = $session_manager->get_logged_in_user();

if ($current_user !== null) {
    $service_provider
        ->get_logger()
        ->info($current_user->get_name() . " ha cerrado sesión");
}

$session_manager->logout();

header('Location: ../index.php');

exit(0);
