<?php

declare(strict_types=1);

session_start();

use \app\config_factory;
use \app\pdo_factory;
use \resena\database\user_repository;

require_once("../src/autoload.php");

$config_factory = new config_factory();
$pdo_factory = new pdo_factory();
$database_connection = $pdo_factory->create($config_factory->create_production());
$user_repository = new user_repository($database_connection);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit(0);
}

if (isset($_POST['visitor'])) {
    $_SESSION['is_visitor'] = true;
    header('Location: ../menu.php');
    exit(0);
}

if (
    !isset($_POST['name'])
    || !isset($_POST['password'])
) {
    header('Location: ../index.php?error=Datos+incorrectos');
    exit(0);
}

$name = trim($_POST['name']);
$password = $_POST['password'];
$user = $user_repository->findExactUserByName($name);

if (
    $user === null
    || !password_verify(
        $password,
        $user->get_password()
    )
) {
    header('Location: ../index.php?error=Usuario+o+contraseña+incorrectos');
    exit(0);
}

$_SESSION['is_visitor'] = false;

$statement = $database_connection->prepare(
    "INSERT INTO sessions (
        session_hash,
        user_id,
        created_at,
        last_activity
    ) VALUES (
        :session_hash,
        :user_id,
        NOW(),
        NOW()
    )"
);

$statement->execute([
    ':session_hash' => session_id(),
    ':user_id' => $user->get_id()
]);

header('Location: ../menu.php');

exit(0);
