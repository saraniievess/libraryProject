<?php

declare(strict_types=1);

namespace app;

class pdo_factory
{

	public function create(
		\stdClass $_config
	): \PDO {

		$dsn = "mysql:dbname={$_config->database};host={$_config->host}";
		$database_connection = new \PDO(
			$dsn,
			$_config->user,
			$_config->password
		);
		$database_connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

		return $database_connection;
	}
}
