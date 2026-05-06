<?php

namespace tools;

class input_handler {
    
    /**
     * @var resource $in
     */
    private $in;

    public function __construct() {
        $file_descriptor=fopen("php://stdin","r");
        if(false===$file_descriptor) {
            throw new \Exception("error");
        }
        $this->in=$file_descriptor;
    }

    public function __destruct() {
        if (is_resource($this->in)) {
            fclose($this->in);
        }
    }

    public function read(): string {
        $line = fgets($this->in);
        return $this->isFalse($line);
    }

    private function isFalse(string|false $value): string {
        if ($value === false) {
            throw new \Exception("error");
        }
        return trim($value);
    }
}