<?php

declare(strict_types=1);

namespace app;

use session\session_manager;
use log\file_logger;

class service_provider
{
    private ?\PDO $pdo = null;
    private ?\stdClass $config = null;
    private ?session_manager $session_manager = null;
    private ?string $session_id = null;
    private ?file_logger $logger = null;

    public function get_logger(): file_logger
    {
        if ($this->logger === null) {

            $this->logger = new file_logger(
                __DIR__ . "/../../../logs/app.log"
            );
        }

        return $this->logger;
    }

    public function get_database_connection(): \PDO
    {
        if ($this->pdo === null) {
            $pdo_factory = new pdo_factory();
            $this->pdo = $pdo_factory->create($this->get_production_configuration());
        }
        return $this->pdo;
    }

    public function get_production_configuration(): \stdClass
    {
        if ($this->config === null) {
            $config_factory = new config_factory();
            $this->config = $config_factory->create_production();
        }
        return $this->config;
    }

    public function get_session_manager(): session_manager
    {
        if ($this->session_manager === null) {
            $this->session_manager = new session_manager(
                $this->get_database_connection(),
                $this->get_session_id()
            );
        }
        return $this->session_manager;
    }

    public function get_session_id(): string
    {
        if ($this->session_id === null) {
            $session_id = session_id();
            if ($session_id === false) {
                throw new \Exception('No se pudo obtener la sesión');
            }
            $this->session_id = $session_id;
        }
        return $this->session_id;
    }
}
