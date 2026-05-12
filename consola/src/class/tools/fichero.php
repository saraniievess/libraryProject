<?php

$dsn = 'mysql:dbname=libreria;host=localhost';
$user = 'root';
$password = '';

$database_connection = new PDO($dsn, $user, $password);
$database_connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

?>
