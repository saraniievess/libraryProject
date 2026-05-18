<?php

declare(strict_types=1);

use \app\config_factory;
use \app\pdo_factory;
use \resena\database\user_repository;
use \resena\model\user;

require_once("src/autoload.php");

$config_factory = new config_factory();
$pdo_factory = new pdo_factory();
$database_connection = $pdo_factory->create($config_factory->create_production());

$user_repository = new user_repository($database_connection);
$user_id = (int)($_POST['user_id'] ?? 0);
$user = $user_repository->findUserById($user_id);

if ($user === null) {
    die("No existe ese usuario");
}

try {
    $user->set_name(trim($_POST['nombre']));
    $user->set_birthdate(
        new DateTime($_POST['fecha'])
    );
    $user->set_password(
        trim($_POST['password'])
    );
    $user->set_role(
        trim($_POST['role'])
    );
    $user_repository->update($user);
    header("Location: listado_users.php");
    exit(0);
} catch (Exception $e) {
    echo $e->getMessage();
}
