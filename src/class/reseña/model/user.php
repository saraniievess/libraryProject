<?php
namespace reseña;

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


    private string $name;
    private \DateTime $birthdate;
}