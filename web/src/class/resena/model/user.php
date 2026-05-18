<?php

namespace resena\model;

use database\model;

class user implements model
{

    //------------------------------------------------------------------
    public function get_tablename(): string
    {
        return self::table;
    }

    public function get_field_list(): array
    {
        return ["name", "date_birthday", "password", "role"];
    }

    public function get_statement_args(): array
    {
        return [
            ":name" => $this->get_name(),
            ":date_birthday" => $this->get_birthdate()->format('Y-m-d'),
            ":password" => $this->get_password(),
            ":role" => $this->get_role()
        ];
    }

    public function set_id(int $id): void
    {
        if ($id > 0) {
            $this->id = $id;
        } else {
            throw new \Exception("id must be larger than zero");
        }
    }

    public function get_id(): int
    {
        return $this->id;
    }
    //------------------------------------------------------------------

    public function __construct(string $_name, \DateTime $_birthdate, string $_password, string $_role)
    {
        if (trim($_name) === "") {
            throw new \Exception("name cannot be empty");
        }
        $this->name = trim($_name);
        $min = \DateTime::createFromFormat("d-m-Y", "01-01-1900");
        $max = new \DateTime();
        if ($_birthdate < $min || $_birthdate > $max) {
            throw new \Exception("the date must be realistic");
        }
        $this->birthdate = $_birthdate;
        if (trim($_password) === "") {
            throw new \Exception("password cannot be empty");
        }
        $this->password = trim($_password);
        $allowed_roles = ["admin", "user"];
        if (!in_array($_role, $allowed_roles)) {
            throw new \Exception("invalid role");
        }
        $this->role = $_role;
    }


    public function get_name(): string
    {
        return $this->name;
    }

    public function get_birthdate(): \DateTime
    {
        return $this->birthdate;
    }

    public function get_password(): string
    {
        return $this->password;
    }
    public function get_role(): string
    {
        return $this->role;
    }

    public function set_name(string $name): void
    {
        $name = trim($name);
        if ($name === "") {
            throw new \Exception("name cannot be empty");
        }
        $this->name = $name;
    }

    public function set_birthdate(\DateTime $_birthdate): void
    {
        $min = \DateTime::createFromFormat("d-m-Y", "01-01-1900");
        $max = new \DateTime();
        if ($_birthdate < $min || $_birthdate > $max) {
            throw new \Exception("the date must be realistic");
        }
        $this->birthdate = $_birthdate;
    }

    public function set_password(string $password)
    {
        $password = trim($password);
        if ($password === "") {
            throw new \Exception("password cannot be empty");
        }
        $this->password = $password;
    }

    public function set_role(string $role)
    {
        $role = trim($role);
        if ($role === "") {
            throw new \Exception("role cannot be empty");
        }
        $this->role = $role;
    }

    private string $name;
    private \DateTime $birthdate;
    private int $id = 0;
    private string $password;
    private string $role;
    const table = "users";
}
