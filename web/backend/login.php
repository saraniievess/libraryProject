<?php

declare(strict_types=1);

session_start();

use \resena\database\user_repository;
use \app\service_provider;

require_once("../src/autoload.php");

$service_provider = new service_provider();
$database_connection = $service_provider->get_database_connection();
$user_repository = new user_repository($database_connection);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit(0);
}

if (isset($_POST['visitor'])) {
    $service_provider
        ->get_logger()
        ->info("Un visitante ha accedido al sistema");
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
    $service_provider
        ->get_logger()
        ->warning($name . " ha intentado iniciar sesión");
    header('Location: ../index.php?error=Usuario+o+contraseña+incorrectos');
    exit(0);
}

$_SESSION['is_visitor'] = false;

$service_provider
    ->get_logger()
    ->info($user->get_name() . " ha iniciado sesión");

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
