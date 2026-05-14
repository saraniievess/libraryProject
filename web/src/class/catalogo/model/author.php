<?php

namespace catalogo\model;

use database\model;

class author implements model {

    //------------------------------------------------------------------
    public function get_tablename() : string {
        return self::table;
    }

    public function get_field_list() : array {
        return ["name", "nationality", "birthdate"];
    }

    public function get_statement_args() : array {
        return [
            ":name" => $this->get_name(),
            ":nationality" => $this->get_nationality(),
            ":birthdate" => $this->get_birthdate()->format('Y-m-d')
        ];
    }

    public function set_id(int $id) : void {
        if ($id > 0) {
            $this->id=$id;
        } else {
            throw new \Exception("id must be larger than zero");
        }
    }

    public function get_id() : int {
        return $this->id;
    }
    //------------------------------------------------------------------


    public function __construct(string $_name, string $_nationality, \DateTime $_birthdate) {
        if(trim($_name) === "") {
            throw new \Exception("name cannot be empty");
        }
        $this->name=trim($_name);

        if(trim($_nationality) === "") {
            throw new \Exception("nationality cannot be empty");
        }
        $this->nationality = trim($_nationality);

        $min = \DateTime::createFromFormat("d-m-Y", "01-01-1900");
        $max = new \DateTime();
        if($_birthdate<$min || $_birthdate>$max) {
            throw new \Exception("the date must be realistic");
        }
        $this->birthdate = $_birthdate;
    }


    public function get_name() : string {
        return $this->name;
    }

    public function get_nationality() : string {
        return $this->nationality;
    }

    public function get_birthdate() : \DateTime {
        return $this->birthdate;
    }

    public function set_name(string $_name) : void {
        if(trim($_name) === "") {
            throw new \Exception("name cannot be empty");
        }
        $this->name=trim($_name);
    }

    public function set_nationality(string $_nationality) : void {
        if(trim($_nationality) === "") {
            throw new \Exception("nationality cannot be empty");
        }
        $this->nationality = trim($_nationality);
    }

    public function set_birthdate(\DateTime $_birthdate) : void {
        $min = \DateTime::createFromFormat("d-m-Y", "01-01-1900");
        $max = new \DateTime();
        if($_birthdate<$min || $_birthdate>$max) {
            throw new \Exception("the date must be realistic");
        }
        $this->birthdate = $_birthdate;
    }


    private int $id = 0;
    private string $name;
    private string $nationality;
    private \DateTime $birthdate;
    const table = "author";
}