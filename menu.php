<?php
declare(strict_types=1);

$dsn = 'mysql:dbname=libreria;host=localhost';
$user = 'root';
$password = '';

$database_connection = new PDO($dsn, $user, $password);
$database_connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

?>
<?php
require("src/autoload.php");

use catalogo\model\book;
use catalogo\database\book_repository;
use tools\input_handler;

$books=[];

$pdo = new book_repository($database_connection);
$input = new input_handler();

while (true) {
    echo "1) Add book".PHP_EOL;
    echo "2) List books".PHP_EOL;
    echo "3) Delete book".PHP_EOL;
    echo "4) Update book".PHP_EOL;
    echo "5) Exit".PHP_EOL;
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
                $_book = new book($_author, $_title, $_house, $_genre, $pages);
                $books[] = $_book;
                $pdo->insertBook($_book);
            }
            catch(\Exception $e) {
                echo "something failed: {$e->getMessage()}".PHP_EOL;
            }
            break;
        case 2:
            $books=$pdo->getAllBooks();
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
            $foundBooks=$pdo->findBooksByTitle($_title);
            foreach ($foundBooks as $book) {
                echo "ID: {$book->get_id()} | {$book->get_title()} by {$book->get_author()} published by {$book->get_house()}. Pages: {$book->get_page_count()}. Genre: {$book->get_genre()}" . PHP_EOL;
            }
            echo "Enter id: ".PHP_EOL;
            $_id = $input->read();
            if (is_numeric($_id)) {
                $id = (int) $_id;
            } else {
                throw new \Exception("please enter a valid number");
            }
            
            try {
                foreach ($foundBooks as $book) {
                    if ($book->get_id()===$id) {
                        $pdo->deleteBook($book);
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
            $foundBooks=$pdo->findBooksByTitle($_title);
            foreach ($foundBooks as $book) {
                echo "ID: {$book->get_id()} | {$book->get_title()} by {$book->get_author()} published by {$book->get_house()}. Pages: {$book->get_page_count()}. Genre: {$book->get_genre()}" . PHP_EOL;
            }
            echo "Enter id: ".PHP_EOL;
            $_id = $input->read();
            if (is_numeric($_id)) {
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
                                    if (is_numeric($_page_count)) {
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
                        $pdo->updateBook($book);
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
            exit(0);
        default:
            echo "please enter a valid number".PHP_EOL;
            break;
    }
}