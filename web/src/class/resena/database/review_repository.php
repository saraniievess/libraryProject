<?php

namespace resena\database;

use resena\model\review;
use database\repository;

class review_repository extends repository
{
    private \PDO $pdo;
    private ?\PDOStatement $findReviewsByTitleStatement = null;
    private ?\PDOStatement $findReviewsByNameStatement = null;
    private string $table = review::table;


    public function __construct(\PDO $_pdo)
    {
        parent::__construct($_pdo);
        $this->pdo = $_pdo;
    }


    /**
     * @return list<review>
     */
    public function getAllReviews(): array
    {
        $statement = $this->pdo->query("SELECT * FROM {$this->table}");
        if ($statement === false) {
            throw new \Exception("database query failed");
        }
        $reviews = [];
        while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $review = new review(
                $row['title'],
                $row['user'],
                (int)$row['ranking'],
                new \DateTime($row['finished_date']),
                $row['info']
            );
            $review->set_id((int)$row['id']);
            $reviews[] = $review;
        }
        return $reviews;
    }

    /**
     * @return list<review>
     */
    public function findReviewByTitle(string $title): array
    {
        if ($this->findReviewsByTitleStatement === null) {
            $this->findReviewsByTitleStatement = $this->pdo->prepare(
                "SELECT * FROM {$this->table} WHERE title LIKE :title"
            );
        }
        $this->findReviewsByTitleStatement->execute([
            ":title" => "%{$title}%"
        ]);
        $rows = $this->findReviewsByTitleStatement->fetchAll();
        $reviews = [];
        foreach ($rows as $row) {
            $review = new review(
                $row['title'],
                $row['user'],
                (int)$row['ranking'],
                new \DateTime($row['finished_date']),
                $row['info']
            );
            $review->set_id((int)$row['id']);
            $reviews[] = $review;
        }
        return $reviews;
    }

    /**
     * @return list<review>
     */
    public function findReviewByName(string $name): array
    {
        if ($this->findReviewsByNameStatement === null) {
            $this->findReviewsByNameStatement = $this->pdo->prepare(
                "SELECT * FROM {$this->table} WHERE user LIKE :name"
            );
        }
        $this->findReviewsByNameStatement->execute([
            ":name" => "%{$name}%"
        ]);
        $rows = $this->findReviewsByNameStatement->fetchAll();
        $reviews = [];
        foreach ($rows as $row) {
            $review = new review(
                $row['title'],
                $row['user'],
                (int)$row['ranking'],
                new \DateTime($row['finished_date']),
                $row['info']
            );
            $review->set_id((int)$row['id']);
            $reviews[] = $review;
        }
        return $reviews;
    }
}
