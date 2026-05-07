<?php

namespace resena\database;

use resena\model\review;

class review_repository {
    private \PDO $pdo;
    private ?\PDOStatement $insertStatement = null;
    private ?\PDOStatement $deleteStatement = null;
    private ?\PDOStatement $updateStatement = null;
    private ?\PDOStatement $findReviewsByTitleStatement = null;

    public function __construct(\PDO $_pdo) {
        $this->pdo = $_pdo;
    }

    public function insertReview (review $review) : void {
        if ($this->insertStatement === null) {
            $this->insertStatement = $this->pdo->prepare(
                "INSERT INTO reviews (title, user, ranking, finished_date, info)
                VALUES (:title, :user, :ranking, :finished_date, :info)"
            );
        }
        $this->insertStatement->execute([
            ":title" => $review->get_title(),
            ":user" => $review->get_user(),
            ":ranking" => $review->get_ranking(),
            ":finished_date" => $review->get_finished_date()->format('Y-m-d'),
            ":info" => $review->get_info()
        ]);
        $statement = $this->pdo->query(
            "SELECT id FROM review ORDER BY id DESC LIMIT 1"
        );
        $id = (int)$statement->fetchColumn();
        $book->set_id($id);
    }

    /**
     * @return review[]
     */
    public function getAllReviews() : array {
        $statement = $this->pdo->query("SELECT * FROM reviews");

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
     * @return review[]
     */
    public function findReviewByTitle (string $title) : array{
        if ($this->findReviewsByTitleStatement === null) {
            $this->findReviewsByTitleStatement = $this->pdo->prepare(
                "SELECT * FROM reviews WHERE title LIKE :title"
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

    public function updateReview (review $review) : void {
        if ($this->updateStatement === null) {
            $this->updateStatement = $this->pdo->prepare(
                "UPDATE reviews SET
                    title = :title,
                    user = :user,
                    ranking = :ranking,
                    finished_date = :finished_date,
                    info = :info
                WHERE id = :id"
            );
        }
        $this->updateStatement->execute([
            ":title" => $review->get_title(),
            ":user" => $review->get_user(),
            ":ranking" => $review->get_ranking(),
            ":finished_date" => $review->get_finished_date()->format('Y-m-d'),
            ":info" => $review->get_info(),
            ":id" => $review->get_id()
        ]);
        if ($this->updateStatement->rowCount() === 0) {
            throw new \Exception("review not found");
        }
    }

    public function deleteReview (review $review) : void {
        if ($this->deleteStatement === null) {
            $this->deleteStatement = $this->pdo->prepare(
                "DELETE FROM reviews WHERE id = :id"
            );
        }
        $this->deleteStatement->execute([
            ":id" => $review->get_id()
        ]);
        if ($this->deleteStatement->rowCount() === 0) {
            throw new \Exception("review not found");
        }
    }
}