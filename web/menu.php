<?php

declare(strict_types=1);

session_start();

use \app\config_factory;
use \app\pdo_factory;
use \session\session_manager;

require_once("src/autoload.php");

$config_factory = new config_factory();
$pdo_factory = new pdo_factory();
$database_connection = $pdo_factory->create($config_factory->create_production());
$session_manager = new session_manager($database_connection, session_id());
$current_user = $session_manager->get_logged_in_user();
$session_manager->commit_last_activity();

$session_link = "";

if ($session_manager->is_visitor()) {
    $session_link = <<<R
    <a href="index.php">Iniciar sesión</a>
    R;
} else {
    $session_link = <<<R
    <a href="logout.php">Cerrar sesión</a>
    R;
}

echo <<<R
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Menú</title>
</head>
<body>
<h1>Menú principal</h1>
<a href="listado_libros.php">Listar libros</a>
<br>
<a href="listado_users.php">Listar usuarios</a>
<br>
<a href="index_form.php">Añadir datos</a>
<br><br>
{$session_link}
</body>
</html>
R;

exit(0);
