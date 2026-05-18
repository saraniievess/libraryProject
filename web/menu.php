<?php

require_once("auth.php");

require_login();

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
	<a href="index_form.html">Añadir datos</a>
</body>

</html>
R;
