<?php

declare(strict_types=1);

session_start();

use \app\config_factory;
use \app\pdo_factory;
use \resena\database\user_repository;
use \resena\model\user;
use \session\session_manager;

require_once("../src/autoload.php");

$config_factory = new config_factory();
$pdo_factory = new pdo_factory();
$database_connection = $pdo_factory->create($config_factory->create_production());

$session_id = session_id();
if ($session_id === false) {
    die('No se pudo obtener la sesión');
}
$session_manager = new session_manager($database_connection, $session_id);
$session_manager->commit_last_activity();
$current_user = $session_manager->get_logged_in_user();

// Login
if (null === $current_user) {
    header('Location: index.php');
    exit(0);
}

$user_repository = new user_repository($database_connection);

$user_id = (int)($_POST['user_id'] ?? 0);

$user = $user_repository->findUserById($user_id);

if (null === $user) {
    die("No existe ese usuario");
}

// ¿Puede editarlo?
$can_edit =
    $current_user->get_id() === $user->get_id()
    || $current_user->get_role() === 'admin';
if (!$can_edit) {
    die("No puedes editar este usuario");
}

if (
    isset($_POST['nombre'])
    && isset($_POST['fecha'])
    && isset($_POST['role'])
) {
    $name = trim($_POST['nombre']);
    $birthdate = new \DateTime(
        $_POST['fecha']
    );
    $role = trim($_POST['role']);
    $user->set_name($name);
    $user->set_birthdate($birthdate);
    $user->set_role($role);
    if (
        isset($_POST['password'])
        && trim($_POST['password']) !== ''
    ) {
        $hashed_password = password_hash(
            $_POST['password'],
            PASSWORD_DEFAULT
        );
        $user->set_password(
            $hashed_password
        );
    }
    $user_repository->update($user);
    echo "Usuario modificado correctamente";
}
exit(0);
