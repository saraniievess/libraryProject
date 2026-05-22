<?php

declare(strict_types=1);

session_start();

use \app\config_factory;
use \app\pdo_factory;
use \view\html_view;
use \view\form_users;
use \resena\database\user_repository;
use \resena\model\user;
use \session\session_manager;

require_once("src/autoload.php");

$config_factory = new config_factory();
$pdo_factory = new pdo_factory();
$database_connection = $pdo_factory->create($config_factory->create_production());
$session_manager = new session_manager($database_connection, session_id());
$current_user = $session_manager->get_logged_in_user();
$session_manager->commit_last_activity();

// Login
if (null === $current_user) {
    header('Location: index.php');
    exit(0);
}

// Admin
if ($current_user->get_role() !== 'admin') {
    die("No tienes permisos para esto");
}

$user_repository = new user_repository($database_connection);

if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['nombre'])
) {
    $nombre = trim($_POST['nombre']);
    $birthdate = new \DateTime(
        $_POST['fecha']
    );
    $password = password_hash(
        $_POST['password'],
        PASSWORD_DEFAULT
    );
    $role = trim($_POST['role']);
    $user = new user(
        $nombre,
        $birthdate,
        $password,
        $role
    );
    $user_repository->insert($user);
    echo "Usuario añadido correctamente";
}

//The presence or absence of "origin" in the query string...
$origin = form_users::origin_new_user;
if (array_key_exists("origin", $_GET)) {

    //...may cause the return path to be different.
    switch ($_GET["origin"]) {
        case form_users::origin_new_user:
            $origin = $_GET["origin"];
            break;
    }
}

$form_users = new form_users(null, $origin, null);
$view = new html_view();
echo $view->create($form_users);

exit(0);
