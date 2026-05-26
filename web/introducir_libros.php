<?php

declare(strict_types=1);

session_start();

use \app\config_factory;
use \app\pdo_factory;
use \view\html_view;
use \view\form_libro;
use \session\session_manager;

require_once("src/autoload.php");

$config_factory = new config_factory();
$pdo_factory = new pdo_factory();
$database_connection = $pdo_factory->create($config_factory->create_production());

$session_id = session_id();
if ($session_id === false) {
    die('No se pudo obtener la sesión');
}
$session_manager = new session_manager($database_connection, $session_id);
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
