<?php

declare(strict_types=1);

use \app\config_factory;
use \app\pdo_factory;
use \view\html_view;
use \view\form_users;
use \resena\database\user_repository;
use \resena\model\user;

require_once("src/autoload.php");

$config_factory = new config_factory();
$pdo_factory = new pdo_factory();
$database_connection = $pdo_factory->create($config_factory->create_production());

$user_repository = new user_repository($database_connection);

$form_users = new form_users();
$view = new html_view();
echo $view->create($form_users);

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['nombre'])) {
    $nombre = trim($_GET['nombre']);
    $fecha = new DateTime($_GET['fecha']);
    $user = new user(
        $nombre,
        $fecha
    );
    $user_repository->insert($user);

    echo "Usuario añadido correctamente";
}

exit(0);
