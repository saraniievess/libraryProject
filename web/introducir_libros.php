<?php

declare(strict_types=1);

session_start();

use \app\service_provider;
use \view\html_view;
use \view\form_libro;

require_once("src/autoload.php");

$service_provider = new service_provider();
$database_connection = $service_provider->get_database_connection();
$session_manager = $service_provider->get_session_manager();
$current_user = $session_manager->get_logged_in_user();
$session_manager->commit_last_activity();

// Login
if ($current_user === null) {
    header('Location: index.php');
    exit(0);
}

// Admin
if ($current_user->get_role() !== 'admin') {
    die("No tienes permisos para esto");
}

$insertion_result = $_GET['insertion'] ?? null;
$form_libro = new form_libro($insertion_result);
$view = new html_view();
echo $view->create($form_libro);

exit(0);
