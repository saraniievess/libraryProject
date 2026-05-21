<?php

declare(strict_types=1);

session_start();

$timeout = 600;

if (
    isset($_SESSION['last_activity'])
    && (time() - $_SESSION['last_activity']) > $timeout
) {
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit(0);
}

$_SESSION['last_activity'] = time();

use \app\config_factory;
use \app\pdo_factory;
use \resena\database\user_repository;
use \session\session_manager;

require_once("src/autoload.php");

$config_factory = new config_factory();
$pdo_factory = new pdo_factory();
$database_connection = $pdo_factory->create($config_factory->create_production());
$session_manager = new session_manager($database_connection, session_id());
$user_repository = new user_repository($database_connection);

$error = "";

if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['name'])
    && isset($_POST['password'])
) {
    $name = trim($_POST['name']);
    $password = $_POST['password'];
    $user = $user_repository->findExactUserByName($name);
    if (
        $user !== null
        && password_verify(
            $password,
            $user->get_password()
        )
    ) {
        $_SESSION['is_visitor'] = false;
        $_SESSION['last_activity'] = time();
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
            ":session_hash" => session_id(),
            ":user_id" => $user->get_id()
        ]);
        header('Location: menu.php');
        exit(0);
    } else {
        $error = "Usuario o contraseña incorrectos";
    }
}

if (isset($_POST['visitor'])) {
    $_SESSION['is_visitor'] = true;
    header('Location: menu.php');
    exit(0);
}

echo <<<R
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>

<h1>Iniciar sesión</h1>

<p>{$error}</p>
<form method="POST">
    <label for="name">Nombre:</label>
    <input type="text" name="name" id="name">
    <br>
    <label for="password">Contraseña:</label>
    <input type="password" name="password" id="password">
    <br>
    <button type="submit">Iniciar sesión</button>
</form>
<br>
<form method="POST">
    <button type="submit" name="visitor">Continuar como visitante</button>
</form>
</body>
</html>
R;
