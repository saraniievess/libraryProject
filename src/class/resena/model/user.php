<?php
namespace resena\model;

class user {
    public function __construct (string $_name, \DateTime $_birthdate) {
        $this->name=trim($_name);    
        if($_name === "") {
            throw new \Exception("name cannot be empty");
        }
        $min = \DateTime::createFromFormat("d-m-Y", "01-01-1900");
        $max = new \DateTime();
        if($_birthdate<$min || $_birthdate>$max) {
            throw new \Exception("the date must be realistic");
        }
        $this->birthdate=$_birthdate;
    }


    public function get_name() : string {
        return $this->name;
    }

    public function get_birthdate() : \DateTime {
        return $this->birthdate;
    }

    public function get_id() : int {
        return $this->id;
    }

    public function set_name(string $name): void {
        $name = trim($name);
        if ($name === "") {
            throw new \Exception("name cannot be empty");
        }
        $this->name = $name;
    }

    public function set_birthdate(\DateTime $_birthdate) : void {
        $min = \DateTime::createFromFormat("d-m-Y", "01-01-1900");
        $max = new \DateTime();
        if ($_birthdate < $min || $_birthdate > $max) {
            throw new \Exception("the date must be realistic");
        }
        $this->birthdate = $_birthdate;
    }

    public function set_id(int $id) : void {
        if (0<=$id) {
            $this->id=$id;
        } else {
            throw new \Exception("id must be larger than zero");
        }
    }

    private string $name;
    private \DateTime $birthdate;
    private int $id;
}