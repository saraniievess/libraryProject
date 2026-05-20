<?php

declare(strict_types=1);

require_once("auth.php");

echo <<<R
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Añadir datos</title>
    <br>
</head>
<body>
<h1>Añadir datos</h1>
R;

if (is_admin()) {
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
