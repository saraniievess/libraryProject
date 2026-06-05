<?php

namespace log;

//PSR-3, si quieres ver recomendaciones de la comunidad de PHP.

class file_logger
{ //implements logger {

	public function __construct(
		string $_file_path
	) {
		$this->file_handle = fopen($_file_path, "a");
	}

	public function __destruct()
	{
		if (false !== $this->file_handle) {
			fclose($this->file_handle);
		}
	}

	public function emergency(string $_msg): void
	{
		$this->add_log($_msg, "EMERGENCY");
	}

	public function alert(string $_msg): void
	{
		$this->add_log($_msg, "ALERT");
	}

	public function critical(string $_msg): void
	{
		$this->add_log($_msg, "CRITICAL");
	}

	public function error(string $_msg): void
	{
		$this->add_log($_msg, "ERROR");
	}

	public function warning(string $_msg): void
	{
		$this->add_log($_msg, "WARNING");
	}

	public function notice(string $_msg): void
	{
		$this->add_log($_msg, "NOTICE");
	}

	public function info(string $_msg): void
	{
		$this->add_log($_msg, "INFO");
	}

	public function debug(string $_msg): void
	{
		$this->add_log($_msg, "DEBUG");
	}

	private function add_log(string $_msg, string $_level): void
	{
		if ($this->file_handle === false) {
			return;
		}
		$now = new \DateTime();
		$log_string = "{$now->format("Y-m-d H:i:s")} [{$_level}] : {$_msg}" . PHP_EOL;
		fwrite($this->file_handle, $log_string);
	}

	/** @var resource | false */
	private $file_handle;
}
