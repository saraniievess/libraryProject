<?php

namespace catalogo\database;

use catalogo\model\book;

class book_repository {
    private \PDO $pdo;
    private ?\PDOStatement $insertStatement = null;
    private ?\PDOStatement $deleteStatement = null;
    private ?\PDOStatement $updateStatement = null;
    private ?\PDOStatement $findBooksByTitleStatement = null;

    public function __construct(\PDO $_pdo) {
        $this->pdo = $_pdo;
    }

    public function insertBook (book $book) : void {
        if ($this->insertStatement === null) {
            $this->insertStatement = $this->pdo->prepare(
                "INSERT INTO book (author, title, house, genre, page_count)
                VALUES (:author, :title, :house, :genre, :page_count)");
        }
        $this->insertStatement->execute([
            ":author" => $book->get_author(),
            ":title" => $book->get_title(),
            ":house" => $book->get_house(),
            ":genre" => $book->get_genre(),
            ":page_count" => $book->get_page_count()
        ]);
        $statement = $this->pdo->query(
            "SELECT id FROM book ORDER BY id DESC LIMIT 1"
        );
        $id = (int)$statement->fetchColumn();
        $book->set_id($id);
    }

    public function updateBook (book $book) : void {
        if ($this->updateStatement === null) {
            $this->updateStatement = $this->pdo->prepare
            ("UPDATE book SET
                title = :title,
                author = :author,
                house = :house,
                genre = :genre,
                page_count = :page_count
            WHERE id = :id");
        }
        $this->updateStatement->execute([
            ":title" => $book->get_title(),
            ":author" => $book->get_author(),
            ":house" => $book->get_house(),
            ":genre" => $book->get_genre(),
            ":page_count" => $book->get_page_count(),
            ":id" => $book->get_id()
        ]);

        if ($this->updateStatement->rowCount() === 0) {
            throw new \Exception("book not found");
        }
    }

    public function deleteBook (book $book) : void {
        if ($this->deleteStatement === null) {
            $this->deleteStatement = $this->pdo->prepare("DELETE FROM book WHERE id = :id");
        }
        $this->deleteStatement->execute([":id" => $book->get_id()]);

        if ($this->deleteStatement->rowCount() === 0) {
            throw new \Exception("book not found");
        }
    }

    /**
     * @return book[]
     */
    public function findBooksByTitle(string $title): array {
        if ($this->findBooksByTitleStatement === null) {
            $this->findBooksByTitleStatement = $this->pdo->prepare("SELECT * FROM book WHERE title LIKE :title");
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
        $statement = $this->pdo->query("SELECT * FROM book");
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