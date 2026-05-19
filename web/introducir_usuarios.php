<?php

declare(strict_types=1);

use \app\config_factory;
use \app\pdo_factory;
use \view\html_view;
use \view\form_users;
use \resena\database\user_repository;
use \resena\model\user;

require_once("src/autoload.php");
require_once("auth.php");

require_login();

if (!is_admin()) {
    die('Acceso denegado');
}

$config_factory = new config_factory();
$pdo_factory = new pdo_factory();
$database_connection = $pdo_factory->create($config_factory->create_production());

$user_repository = new user_repository($database_connection);

$insert_result = null;
if (array_key_exists("insercion", $_GET)) {
    $insert_result = $_GET["insercion"];
}

$user = null;

$form_users = new form_users($user, $insert_result);
$view = new html_view();
echo $view->create($form_users);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre'])) {
    $nombre = trim($_POST['nombre']);
    $fecha = new DateTime($_POST['fecha']);
    $password = trim($_POST['password']);
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $role = trim($_POST['role']);

    $user = new user(
        $nombre,
        $fecha,
        $password_hash,
        $role
    );

    $user_repository->insert($user);
}

exit(0);
