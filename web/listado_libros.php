<?php
declare(strict_types=1);

use model\book;

require_once("src/autoload.php");

$json = file_get_contents("config/config.json");
if ($json === false) {
    throw new Exception("config file failed");
}

$config = json_decode($json);
if ($config === null) {
    throw new Exception("invalid json");
}

$dsn = "mysql:dbname={$config->database};host={$config->host}";
$database_connection = new PDO(
    $dsn,
    $config->user,
    $config->password
);
$database_connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
?>
<?php

$books = getAllBooks($database_connection);

echo <<<R
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Libros</title>
</head>
<body>
    <h1>Listado de libros</h1>
    <ul>
R;
        foreach ($books as $book) {
            echo "<li> ID: {$book->get_id()} | {$book->get_title()} by {$book->get_author()} published by {$book->get_house()}. Pages: {$book->get_page_count()}. Genre: {$book->get_genre()} </li>";}
echo <<<R
    </ul>
    <a href="resenas_libro.html">Reseñas alas de sangre</a>
    <a href="index.html">Volver</a>
</body>
</html>
R;


function getAllBooks(PDO $pdo) : array {
    $statement = $pdo->query("SELECT * FROM book");
    if ($statement === false) {
        throw new \Exception("database query failed");
    }
    $books = [];

    while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
        $book = new book(
            $row['author'],
            $row['title'],
            $row['house'],
            $row['genre'],
            (int)$row['page_count']
        );
        $book->set_id((int)$row['id']);
        $books[] = $book;
    }
    return $books;
}