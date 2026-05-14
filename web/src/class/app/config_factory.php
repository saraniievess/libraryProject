<?php
declare(strict_types=1);
namespace app;

class config_factory {

	public function create_production() : \stdClass {

		$json = file_get_contents(__DIR__."/../../../config/config.json");
		if ($json === false) {
			throw new \Exception("config file failed");
		}

		$config = json_decode($json);
		if ($config === null) {
			throw new \Exception("invalid json");
		}

		return $config;
	}
}
