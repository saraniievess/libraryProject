<?php

declare(strict_types=1);

session_start();

ini_set("display_errors", "1");
ini_set("error_reporting", "-1");

use \app\config_factory;
use \app\pdo_factory;
use \view\html_view;
use \view\form_resena;
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

if ($session_manager->is_visitor()) {
    die("No tienes permisos para esto");
}

// Login
if ($current_user === null) {
    header('Location: index.php');
    exit(0);
}

$form_resena = new form_resena();
$view = new html_view();
echo $view->create($form_resena);

exit(0);
