<?php

require_once("auth.php");

require_login();

$session_link = "";

if (is_visitor()) {

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
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Librería</title>
</head>

<body>
	<h1>Librería</h1>
	<a href="listado_libros.php">Listar libros</a><br>
	<a href="listado_users.php">Listar Usuarios</a><br>
	<a href="index_form.php">Añadir datos</a>
	<br><br>
	{$session_link}
</body>

</html>
R;
