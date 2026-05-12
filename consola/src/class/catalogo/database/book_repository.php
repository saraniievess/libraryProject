<?php

namespace catalogo\database;

use catalogo\model\book;
use database\repository;

class book_repository extends repository {
    private \PDO $pdo;
    private ?\PDOStatement $findBooksByTitleStatement = null;
    private string $table = book::table;


    public function __construct(\PDO $_pdo) {
        parent::__construct($_pdo);
        $this->pdo = $_pdo;
    }


    /**
     * @return book[]
     */
    public function findBooksByTitle(string $title): array {
        if ($this->findBooksByTitleStatement === null) {
            $this->findBooksByTitleStatement = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE title LIKE :title");
        }
        $this->findBooksByTitleStatement->execute([
            ":title" => "%{$title}%"
        ]);
        $rows = $this->findBooksByTitleStatement->fetchAll();

        $books = [];

        foreach ($rows as $row) {
            $book = new book(
                $row['author'],
                $row['title'],
                $row['house'],
                $row['genre'],
                (int)$row['page_count']
            );
            $book->set_id((int)$row['id']);
            $books[] = $book;
        }

        return $books;
    }


    /**
     * @return book[]
     */
    public function getAllBooks() : array {
        $statement = $this->pdo->query("SELECT * FROM {$this->table}");
        if ($statement === false) {
            throw new \Exception("database query failed");
        }
        $books = [];

        while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $book = new book(
                $row['author'],
                $row['title'],
                $row['house'],
                $row['genre'],
                (int)$row['page_count']
            );
            $book->set_id((int)$row['id']);
            $books[] = $book;
        }

        return $books;
    }
}