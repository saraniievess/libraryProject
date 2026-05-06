<?php
declare(strict_types=1);
namespace test_review;

use PHPUnit\Framework\TestCase;

class review extends TestCase {
    
    public function test_title_cannot_be_empty() : void {

		$this->ExpectException(\Exception::class);
		$this->ExpectExceptionMessage("title cannot be empty");
		$review=new \review(
			"",
			"user",
			5,
			new \DateTime("2024-08-24"),
            "info"
		);
	}

    public function test_title_is_trimmed() : void {

		$review=new \review(
			"  title   ",
			"user",
			5,
			new \DateTime("2024-08-24"),
            "info"
		);

		$this->assertEquals("title", $review->get_title());
	}

    public function test_user_cannot_be_empty() : void {

		$this->ExpectException(\Exception::class);
		$this->ExpectExceptionMessage("user cannot be empty");
		$review=new \review(
			"title",
			"",
			5,
			new \DateTime("2024-08-24"),
            "info"
		);
	}

    public function test_user_is_trimmed() : void {

		$review=new \review(
			"title",
			"  user  ",
			5,
			new \DateTime("2024-08-24"),
            "info"
		);

		$this->assertEquals("user", $review->get_user());
	}

    public function test_ranking() : void {

		$this->ExpectException(\Exception::class);
		$this->ExpectExceptionMessage("ranking must be between 1 and 5");
		$review=new \review(
			"title",
			"user",
			0,
			new \DateTime("2024-08-24"),
            "info"
		);
	}

    public function test_ranking2() : void {

		$this->ExpectException(\Exception::class);
		$this->ExpectExceptionMessage("ranking must be between 1 and 5");
		$review=new \review(
			"title",
			"user",
			6,
			new \DateTime("2024-08-24"),
            "info"
		);
	}

    public function test_date() : void {

		$this->ExpectException(\Exception::class);
		$this->ExpectExceptionMessage("the date must be realistic");
		$review=new \review(
			"title",
			"user",
			5,
			new \DateTime("1800-08-24"),
            "info"
		);
	}

    public function test_date2() : void {

		$this->ExpectException(\Exception::class);
		$this->ExpectExceptionMessage("the date must be realistic");
		$review=new \review(
			"title",
			"user",
			5,
			new \DateTime("2026-08-24"),
            "info"
		);
	}
}