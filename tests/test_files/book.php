<?php
declare(strict_types=1);
namespace test_book;

use PHPUnit\Framework\TestCase;

class book extends TestCase {

// TENGO QUE HACER LO DE "    "

	public function test_author_cannot_be_empty() : void {

		$this->ExpectException(\Exception::class);
		$this->ExpectExceptionMessage("author cannot be empty");
		$book=new \book(
			"",
			"title",
			"house",
			"genre",
			99
		);
	}

	public function test_author_is_trimmed() : void {

		$book=new \book(
			"  Daniel  Pastor  ",
			"title",
			"house",
			"genre",
			99
		);

		$this->assertEquals("Daniel  Pastor", $book->get_author());
	}

	public function test_author_cannot_be_empty2() : void {

		$this->ExpectException(\Exception::class);
		$this->ExpectExceptionMessage("author cannot be empty");
		$book=new \book(
			"    ",
			"title",
			"house",
			"genre",
			99
		);
	}

	public function test_title_cannot_be_empty() : void {

		$this->ExpectException(\Exception::class);
		$this->ExpectExceptionMessage("title cannot be empty");
		$book=new \book(
			"a",
			"",
			"house",
			"genre",
			99
		);
	}

	public function test_title_is_trimmed() : void {

		$book=new \book(
			"a",
			" titulo   ",
			"house",
			"genre",
			99
		);

		$this->assertEquals("titulo", $book->get_title());
	}

	public function test_house_cannot_be_empty() : void {

		$this->ExpectException(\Exception::class);
		$this->ExpectExceptionMessage("house cannot be empty");
		$book=new \book(
			"a",
			"b",
			"",
			"genre",
			99
		);
	}

	public function test_house_is_trimmed() : void {

		$book=new \book(
			"a",
			"b",
			"   house  ",
			"genre",
			99
		);

		$this->assertEquals("house", $book->get_house());
	}

	public function test_genre_cannot_be_empty() : void {

		$this->ExpectException(\Exception::class);
		$this->ExpectExceptionMessage("genre cannot be empty");
		$book=new \book(
			"a",
			"b",
			"c",
			"",
			99
		);
	}

	public function test_genre_is_trimmed() : void {

		$book=new \book(
			"a",
			"b",
			"c",
			"  genre  ",
			99
		);

		$this->assertEquals("genre", $book->get_genre());
	}

	public function test_pages_must_be_larger_than_zero() : void {

		$this->ExpectException(\Exception::class);
		$this->ExpectExceptionMessage("page count must be larger than zero");
		$book=new \book(
			"a",
			"b",
			"c",
			"d",
			-99
		);
	}

	public function test_pages_must_be_larger_than_zero_again() : void {

		$this->ExpectException(\Exception::class);
		$this->ExpectExceptionMessage("page count must be larger than zero");
		$book=new \book(
			"a",
			"b",
			"c",
			"d",
			0
		);
	}

	public function test_pages() : void {

		$book=new \book(
			"a",
			"b",
			"c",
			"d",
			10
		);

		$this->assertEquals(10, $book->get_page_count());
	}

	public function test_author() : void {

		$book=new \book(
			"a",
			"b",
			"c",
			"d",
			10
		);

		$this->assertEquals("a", $book->get_author());
	}

	public function test_title() : void {

		$book=new \book(
			"a",
			"b",
			"c",
			"d",
			10
		);

		$this->assertEquals("b", $book->get_title());
	}

	public function test_house() : void {

		$book=new \book(
			"a",
			"b",
			"c",
			"d",
			10
		);

		$this->assertEquals("c", $book->get_house());
	}

	public function test_genre() : void {

		$book=new \book(
			"a",
			"b",
			"c",
			"d",
			10
		);

		$this->assertEquals("d", $book->get_genre());
	}
}

