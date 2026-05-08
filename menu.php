<?php
declare(strict_types=1);

$config = parse_ini_file("config/config.ini");
if ($config === false) {
    throw new Exception("config file failed");
}
$dsn = "mysql:dbname={$config['database']};host={$config['host']}";
$database_connection = new PDO(
    $dsn,
    $config['user'],
    $config['password']
);
$database_connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

?>
<?php
require("src/autoload.php");

use catalogo\model\book;
use catalogo\database\book_repository;
use tools\input_handler;
use resena\model\user;
use resena\database\user_repository;
use resena\model\review;
use resena\database\review_repository;

$bookRepository = new book_repository($database_connection);
$userRepository = new user_repository($database_connection);
$reviewRepository = new review_repository($database_connection);
$input = new input_handler();

while (true) {
    echo "1) Add book".PHP_EOL;
    echo "2) List books".PHP_EOL;
    echo "3) Delete book".PHP_EOL;
    echo "4) Update book".PHP_EOL;
    echo "5) Add user".PHP_EOL;
    echo "6) List user".PHP_EOL;
    echo "7) Delete user".PHP_EOL;
    echo "8) Update user".PHP_EOL;
    echo "9) Add review".PHP_EOL;
    echo "10) List reviews".PHP_EOL;
    echo "11) Delete review".PHP_EOL;
    echo "12) Update review".PHP_EOL;
    echo "13) Exit".PHP_EOL;
    $_opcion = $input->read();

    switch ($_opcion) {
        case 1:
            echo "Enter the title: ".PHP_EOL;
            $_title = $input->read();
            echo "Enter the author: ".PHP_EOL;
            $_author = $input->read();
            echo "Enter the genre: ".PHP_EOL;
            $_genre = $input->read();
            echo "Enter the house: ".PHP_EOL;
            $_house = $input->read();
            echo "Enter the number of pages: ".PHP_EOL;
            $_pages = $input->read();

            if (!ctype_digit($_pages)) {
                throw new \Exception("please enter a valid number of pages");
            }

            $pages=(int) $_pages;

            try {
                $book = new book($_author, $_title, $_house, $_genre, $pages);
                $bookRepository->insert($book);
            }
            catch(\Exception $e) {
                echo "something failed: {$e->getMessage()}".PHP_EOL;
            }
            break;
        case 2:
            $books=$bookRepository->getAllBooks();
            foreach ($books as $book) {
                echo "ID: {$book->get_id()} | {$book->get_title()} by {$book->get_author()} published by {$book->get_house()}. Pages: {$book->get_page_count()}. Genre: {$book->get_genre()}" . PHP_EOL;
            }
            break;
        case 3:
            echo "Enter the title: ".PHP_EOL;
            $_title = $input->read();
            if ($_title==="") {
                throw new \Exception("title cannot be empty");
            }
            $foundBooks=$bookRepository->findBooksByTitle($_title);
            foreach ($foundBooks as $book) {
                echo "ID: {$book->get_id()} | {$book->get_title()} by {$book->get_author()} published by {$book->get_house()}. Pages: {$book->get_page_count()}. Genre: {$book->get_genre()}" . PHP_EOL;
            }
            echo "Enter id: ".PHP_EOL;
            $_id = $input->read();
            if (ctype_digit($_id)) {
                $id = (int) $_id;
            } else {
                throw new \Exception("please enter a valid number");
            }
            
            try {
                foreach ($foundBooks as $book) {
                    if ($book->get_id()===$id) {
                        $bookRepository->delete($book);
                    }
                }
            }
            catch(\Exception $e) {
                echo "something failed: {$e->getMessage()}".PHP_EOL;
            }
            break;
        case 4:
            echo "Enter the title: ".PHP_EOL;
            $_title = $input->read();
            if ($_title==="") {
                throw new \Exception("title cannot be empty");
            }
            $foundBooks=$bookRepository->findBooksByTitle($_title);
            foreach ($foundBooks as $book) {
                echo "ID: {$book->get_id()} | {$book->get_title()} by {$book->get_author()} published by {$book->get_house()}. Pages: {$book->get_page_count()}. Genre: {$book->get_genre()}" . PHP_EOL;
            }
            echo "Enter id: ".PHP_EOL;
            $_id = $input->read();
            if (ctype_digit($_id)) {
                $id = (int) $_id;
            } else {
                throw new \Exception("please enter a valid number");
            }
            try {
                $found=false;
                foreach ($foundBooks as $book) {
                    if ($book->get_id()===$id) {
                        while (true) {
                            $found=true;
                            echo "What do you want to do?".PHP_EOL;
                            echo "1) Change title".PHP_EOL;
                            echo "2) Change author".PHP_EOL;
                            echo "3) Change house".PHP_EOL;
                            echo "4) Change genre".PHP_EOL;
                            echo "5) Change page count".PHP_EOL;
                            echo "6) Nothing".PHP_EOL;
                            $_opcion = $input->read();

                            switch ($_opcion) {
                                case 1:
                                    echo "New title: ".PHP_EOL;
                                    $_title = $input->read();
                                    $book->set_title($_title);
                                    break;
                                case 2:
                                    echo "New author: ".PHP_EOL;
                                    $_author = $input->read();
                                    $book->set_author($_author);
                                    break;
                                case 3:
                                    echo "New house: ".PHP_EOL;
                                    $_house = $input->read();
                                    $book->set_house($_house);
                                    break;
                                case 4:
                                    echo "New genre: ".PHP_EOL;
                                    $_genre = $input->read();
                                    $book->set_genre($_genre);
                                    break;
                                case 5:
                                    echo "New page count: ".PHP_EOL;
                                    $_page_count = $input->read();
                                    if (ctype_digit($_page_count)) {
                                        $page_count = (int) $_page_count;
                                        $book->set_page_count($page_count);
                                    } else {
                                        echo "please enter a valid number".PHP_EOL;
                                    }
                                    break;
                                case 6:
                                    break(2);
                                default:
                                    echo "please enter a valid number".PHP_EOL;
                                    break;
                            }
                        }
                        $bookRepository->update($book);
                    }
                }
                if (!$found) {
                    echo "book not found".PHP_EOL;
                }
            }
            catch(\Exception $e) {
                echo "something failed: {$e->getMessage()}".PHP_EOL;
            }
            break;
        case 5:
            echo "Enter the name: ".PHP_EOL;
            $_name = $input->read();
            echo "Enter the date of birth (YYYY-mm-dd): ".PHP_EOL;
            $_date = $input->read();
            if($_date === "") {
                throw new \Exception("date of birth cannot be empty");
            }
            $date= new \DateTime($_date);
            try {
                $user = new user($_name, $date);
                $userRepository->insert($user);
            }
            catch(\Exception $e) {
                echo "something failed: {$e->getMessage()}".PHP_EOL;
            }
            break;
        case 6:
            $users=$userRepository->getAllUsers();
            foreach ($users as $user) {
                echo "ID: {$user->get_id()} | {$user->get_name()} -> {$user->get_birthdate()->format('Y-m-d')}" . PHP_EOL;
            }
            break;
        case 7:
            echo "Enter the name: ".PHP_EOL;
            $_name = $input->read();
            if ($_name==="") {
                throw new \Exception("name cannot be empty");
            }
            $foundUsers=$userRepository->findUserByName($_name);
            foreach ($foundUsers as $user) {
                echo "ID: {$user->get_id()} | {$user->get_name()} -> {$user->get_birthdate()->format('Y-m-d')}" . PHP_EOL;
            }
            echo "Enter id: ".PHP_EOL;
            $_id = $input->read();
            if (ctype_digit($_id)) {
                $id = (int) $_id;
            } else {
                throw new \Exception("please enter a valid number");
            }
            try {
                foreach ($foundUsers as $user) {
                    if ($user->get_id()===$id) {
                        $userRepository->delete($user);
                    }
                }
            }
            catch(\Exception $e) {
                echo "something failed: {$e->getMessage()}".PHP_EOL;
            }
            break;
        case 8:
            echo "Enter the name: ".PHP_EOL;
            $_name = $input->read();
            if ($_name==="") {
                throw new \Exception("name cannot be empty");
            }
            $foundUsers=$userRepository->findUserByName($_name);
            foreach ($foundUsers as $user) {
                echo "ID: {$user->get_id()} | {$user->get_name()} -> {$user->get_birthdate()->format('Y-m-d')}" . PHP_EOL;
            }
            echo "Enter id: ".PHP_EOL;
            $_id = $input->read();
            if (ctype_digit($_id)) {
                $id = (int) $_id;
            } else {
                throw new \Exception("please enter a valid number");
            }
            try {
                $found=false;
                foreach ($foundUsers as $user) {
                    if ($user->get_id()===$id) {
                        while (true) {
                            $found=true;
                            echo "What do you want to do?".PHP_EOL;
                            echo "1) Change name".PHP_EOL;
                            echo "2) Change date of birth".PHP_EOL;
                            echo "3) Nothing".PHP_EOL;
                            $_opcion = $input->read();

                            switch ($_opcion) {
                                case 1:
                                    echo "New name: ".PHP_EOL;
                                    $_name = $input->read();
                                    $user->set_name($_name);
                                    break;
                                case 2:
                                    echo "New date of birth: ".PHP_EOL;
                                    $_date = $input->read();
                                    $date = new \DateTime($_date);
                                    $user->set_birthdate($date);
                                    break;
                                case 3:
                                    break(2);
                                default:
                                    echo "please enter a valid number".PHP_EOL;
                                    break;
                            }
                        }
                        $userRepository->update($user);
                    }
                }
                if (!$found) {
                    echo "user not found".PHP_EOL;
                }
            }
            catch(\Exception $e) {
                echo "something failed: {$e->getMessage()}".PHP_EOL;
            }
            break;
        case 9:
            echo "Book title: ".PHP_EOL;
            $_title = $input->read();
            echo "User: ".PHP_EOL;
            $_user = $input->read();
            echo "Ranking (1-5): ".PHP_EOL;
            $_ranking = $input->read();
            echo "Finished date (YYYY-mm-dd): ".PHP_EOL;
            $_date = $input->read();
            echo "Info: ".PHP_EOL;
            $_info = $input->read();
            if (!ctype_digit($_ranking)) {
                throw new \Exception("ranking must be numeric");
            }
            $ranking = (int)$_ranking;
            $date = new \DateTime($_date);
            try {
                $review = new review(
                    $_title,
                    $_user,
                    $ranking,
                    $date,
                    $_info
                );
                $reviewRepository->insert($review);
            } catch(\Exception $e) {
                echo "something failed: {$e->getMessage()}".PHP_EOL;
            }
            break;
        case 10:
            $reviews = $reviewRepository->getAllReviews();
            foreach ($reviews as $review) {
                echo
                "ID: {$review->get_id()} | ".
                "{$review->get_title()} | ".
                "{$review->get_user()} | ".
                "Ranking: {$review->get_ranking()} | ".
                "Finished: {$review->get_finished_date()->format('Y-m-d')} | ".
                "{$review->get_info()}".PHP_EOL;
            }
            break;
        case 11:
            echo "Review title: ".PHP_EOL;
            $_title = $input->read();
            $foundReviews = $reviewRepository->findReviewByTitle($_title);
            foreach ($foundReviews as $review) {
                echo
                "ID: {$review->get_id()} | ".
                "{$review->get_title()} | ".
                "{$review->get_user()} | ".
                "Ranking: {$review->get_ranking()} | ".
                "Finished: {$review->get_finished_date()->format('Y-m-d')} | ".
                "{$review->get_info()}".PHP_EOL;
            }
            echo "Enter id: ".PHP_EOL;
            $_id = $input->read();
            $id = (int)$_id;
            foreach ($foundReviews as $review) {
                if ($review->get_id() === $id) {
                    $reviewRepository->delete($review);
                }
            }
            break;
        case 12:
            echo "Review title: ".PHP_EOL;
            $_title = $input->read();
            $foundReviews = $reviewRepository->findReviewByTitle($_title);
            foreach ($foundReviews as $review) {
                echo
                "ID: {$review->get_id()} | ".
                "{$review->get_title()} | ".
                "{$review->get_user()} | ".
                "Ranking: {$review->get_ranking()} | ".
                "Finished: {$review->get_finished_date()->format('Y-m-d')} | ".
                "{$review->get_info()}".PHP_EOL;
            }
            echo "Enter id: ".PHP_EOL;
            $_id = $input->read();
            $id = (int)$_id;
            foreach ($foundReviews as $review) {
                if ($review->get_id() === $id) {
                while (true) {
                    echo "What do you want to change?".PHP_EOL;
                    echo "1) Title".PHP_EOL;
                    echo "2) User".PHP_EOL;
                    echo "3) Ranking".PHP_EOL;
                    echo "4) Info".PHP_EOL;
                    echo "5) Finished date".PHP_EOL;
                    echo "6) Nothing".PHP_EOL;
                    $_opcion = $input->read();
                    switch ($_opcion) {
                        case 1:
                            echo "New title: ".PHP_EOL;
                            $_title = $input->read();
                            $review->set_title($_title);
                            break;
                        case 2:
                            echo "New user: ".PHP_EOL;
                            $_user = $input->read();
                            $review->set_user($_user);
                            break;
                        case 3:
                            echo "New ranking: ".PHP_EOL;
                            $_ranking = $input->read();
                            $review->set_ranking((int)$_ranking);
                            break;
                        case 4:
                            echo "New info: ".PHP_EOL;
                            $_info = $input->read();
                            $review->set_info($_info);
                            break;
                        case 5:
                            echo "New finished date: ".PHP_EOL;
                            $_date = $input->read();
                            $date = new \DateTime($_date);
                            $review->set_finished_date($date);
                            break;
                        case 6:
                            break(2);
                        default:
                            echo "please enter a valid number".PHP_EOL;
                            break;
                    }
                }
                $reviewRepository->update($review);
                }
            }
            break;
        case 13:
            exit(0);
        default:
            echo "please enter a valid number".PHP_EOL;
            break;
    }
}