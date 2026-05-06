<?php
namespace reseña;

class review {
    public function __construct(string $_title, string $_user, int $_ranking, \DateTime $_finished_date, string $_info) {
        if($_title === "") {
            throw new \Exception("title cannot be empty");
        }
        $this->title=trim($_title);

        if($_user === "") {
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


    private string $title;
    private string $user;
    private int $ranking;
    private \DateTime $finished_date;
    private string $info;
}