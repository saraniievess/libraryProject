<?php
namespace catalogo\model;

class book {
    public function __construct(string $_author, string $_title, string $_house, string $_genre, int $_page_count) {
        if($_author === "") {
            throw new \Exception("author cannot be empty");
        }
        $this->author=trim($_author);

        
        if($_title === "") {
            throw new \Exception("title cannot be empty");
        }
        $this->title=trim($_title);
        
        
        if($_house === "") {
            throw new \Exception("house cannot be empty");
        }
        $this->house=trim($_house);

        
        if($_genre === "") {
            throw new \Exception("genre cannot be empty");
        }
        $this->genre=trim($_genre);

        if(0 >= $_page_count) {
            throw new \Exception("page count must be larger than zero");
        }
        $this->page_count=$_page_count;
    }


    public function get_author() : string {
        return $this->author;
    }

    public function get_title() : string {
        return $this->title;
    }

    public function get_house() : string {
        return $this->house;
    }

    public function get_genre() : string {
        return $this->genre;
    }

    public function get_page_count() : int {
        return $this->page_count;
    }

    public function get_id() : int {
        return $this->id;
    }

    public function set_author(string $author): void {
        $author = trim($author);
        if ($author === "") {
            throw new \Exception("author cannot be empty");
        }
        $this->author = $author;
    }

    public function set_title(string $title): void {
        $title = trim($title);
        if ($title === "") {
            throw new \Exception("title cannot be empty");
        }
        $this->title = $title;
    }

    public function set_house(string $house): void {
        $house = trim($house);
        if ($house === "") {
            throw new \Exception("publisher cannot be empty");
        }
        $this->house = $house;
    }

    public function set_genre(string $genre): void {
        $genre = trim($genre);
        if ($genre === "") {
            throw new \Exception("genre cannot be empty");
        }
        $this->genre = $genre;
    }

    public function set_page_count(int $page_count): void {
        if ($page_count <= 0) {
            throw new \Exception("page count must be larger than zero");
        }
        $this->page_count = $page_count;
    }

    public function set_id(int $id) : void {
        if (0<=$id) {
            $this->id=$id;
        } else {
            throw new \Exception("id must be larger than zero");
        }
    }

    private int $id=0;
    private string $author;
    private string $title;
    private string $house;
    private string $genre;
    private int $page_count;
};