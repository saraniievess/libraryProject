<?php

declare(strict_types=1);

session_start();

ini_set("display_errors", "1");
ini_set("error_reporting", "-1");

use \app\service_provider;
use \view\html_view;
use \view\form_resena;

require_once("src/autoload.php");

$service_provider = new service_provider();
$database_connection = $service_provider->get_database_connection();
$session_manager = $service_provider->get_session_manager();
$current_user = $session_manager->get_logged_in_user();
$session_manager->commit_last_activity();

if ($session_manager->is_visitor()) {
    die("No tienes permisos para esto");
}

// Login
if ($current_user === null) {
    header('Location: index.php');
    exit(0);
}

$insertion_result = $_GET['insertion'] ?? null;
$form_resena = new form_resena($insertion_result);
$view = new html_view();
echo $view->create($form_resena);

exit(0);
