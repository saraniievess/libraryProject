<?php

namespace catalogo\database;

use database\repository;
use catalogo\model\author;

class author_repository extends repository {
    private \PDO $pdo;
    private ?\PDOStatement $findAuthorByNameStatement = null;
    private string $table = author::table;


    public function __construct(\PDO $_pdo) {
        parent::__construct($_pdo);
        $this->pdo = $_pdo;
    }


    /**
     * @return author[]
     */
    public function getAllAuthors() : array {
        $statement = $this->pdo->query("SELECT * FROM {$this->table}");
        if ($statement === false) {
            throw new \Exception("database query failed");
        }
        $authors = [];
        while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $author = new author(
                $row['name'],
                $row['nationality'],
                new \DateTime($row['birthdate'])
            );
            $author->set_id((int)$row['id']);
            $authors[] = $author;
        }
        return $authors;
    }

    /**
     * @return author[]
     */
    public function findAuthorByName (string $name) : array{
        if ($this->findAuthorByNameStatement === null) {
            $this->findAuthorByNameStatement = $this->pdo->prepare(
                "SELECT * FROM {$this->table} WHERE name LIKE :name"
            );
        }
        $this->findAuthorByNameStatement->execute([
            ":name" => "%{$name}%"
        ]);
        $rows = $this->findAuthorByNameStatement->fetchAll();
        $authors = [];
        foreach ($rows as $row) {
            $author = new author(
                $row['name'],
                $row['nationality'],
                new \DateTime($row['birthdate'])
            );
            $author->set_id((int)$row['id']);
            $authors[] = $author;
        }
        return $authors;
    }
}