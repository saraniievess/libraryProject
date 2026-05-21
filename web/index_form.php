<?php

declare(strict_types=1);

session_start();

require_once("src/autoload.php");

use \app\config_factory;
use \app\pdo_factory;
use \session\session_manager;

$config_factory = new config_factory();
$pdo_factory = new pdo_factory();
$database_connection = $pdo_factory->create($config_factory->create_production());
$session_manager = new session_manager($database_connection, session_id());
$current_user = $session_manager->get_logged_in_user();
$session_manager->commit_last_activity();

echo <<<R
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Añadir datos</title>
</head>
<body>
<h1>Añadir datos</h1>
R;

// Admin
if (
    null !== $current_user
    && $current_user->get_role() === 'admin'
) {
    echo <<<R
    <a href="introducir_libros.php">Añadir libro</a>
    <br>
    <a href="introducir_usuarios.php">Añadir usuario</a>
    <br>
    R;
}

echo <<<R
<a href="introducir_resenas.php">Añadir reseña</a>
<br><br>
<a href="menu.php">Volver al menú</a>
</body>
</html>
R;

exit(0);
