<?php
namespace resena\model;

use database\model;

class review implements model {

    //------------------------------------------------------------------
    public function get_tablename() : string {
        return self::table;
    }

    public function get_field_list() : array {
        return ["title", "user", "ranking", "finished_date", "info"];
    }

    public function get_statement_args() : array {
        return [
            ":title" => $this->get_title(),
            ":user" => $this->get_user(),
            ":ranking" => $this->get_ranking(),
            ":finished_date" => $this->get_finished_date()->format('Y-m-d'),
            ":info" => $this->get_info()
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

    public function __construct(string $_title, string $_user, int $_ranking, \DateTime $_finished_date, string $_info) {
        if(trim($_title) === "") {
            throw new \Exception("title cannot be empty");
        }
        $this->title=trim($_title);

        if(trim($_user) === "") {
            throw new \Exception("user cannot be empty");
        }
        $this->user=trim($_user);
    
        if(0 >= $_ranking || 5 < $_ranking) {
            throw new \Exception("ranking must be between 1 and 5");
        }
        $this->ranking=$_ranking;

        $min = \DateTime::createFromFormat("d-m-Y", "01-01-1900");
        $max = new \DateTime();

        if($_finished_date<$min || $_finished_date>$max) {
            throw new \Exception("the date must be realistic");
        }
        $this->finished_date=$_finished_date;

        $this->info=$_info;
    }


    public function get_title() : string {
        return $this->title;
    }

    public function get_user() : string {
        return $this->user;
    }

    public function get_ranking() : int {
        return $this->ranking;
    }

    public function get_finished_date() : \DateTime {
        return $this->finished_date;
    }

    public function get_info() : string {
        return $this->info;
    }

    public function set_title(string $_title) : void {
        $_title = trim($_title);
        if ($_title === "") {
            throw new \Exception("title cannot be empty");
        }
        $this->title = $_title;
    }

    public function set_user(string $_user) : void {
        $_user = trim($_user);
        if ($_user === "") {
            throw new \Exception("user cannot be empty");
        }
        $this->user = $_user;
    }

    public function set_ranking(int $_ranking) : void {
        if ($_ranking < 1 || $_ranking > 5) {
            throw new \Exception("ranking must be between 1 and 5");
        }
        $this->ranking = $_ranking;
    }

    public function set_finished_date(\DateTime $_finished_date) : void {
        $min = \DateTime::createFromFormat("d-m-Y", "01-01-1900");
        $max = new \DateTime();
        if ($_finished_date < $min || $_finished_date > $max) {
            throw new \Exception("the date must be realistic");
        }
        $this->finished_date = $_finished_date;
    }

    public function set_info(string $_info) : void {
        $this->info = trim($_info);
    }


    private string $title;
    private string $user;
    private int $ranking;
    private \DateTime $finished_date;
    private string $info;
    private int $id = 0;
    const table = "reviews";
}