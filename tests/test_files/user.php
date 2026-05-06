<?php
declare(strict_types=1);
namespace test_user;

use PHPUnit\Framework\TestCase;

class user extends TestCase {

    public function test_name_cannot_be_empty() : void {

		$this->ExpectException(\Exception::class);
		$this->ExpectExceptionMessage("name cannot be empty");
		$user=new \user(
			"",
            new \DateTime("2024-08-24")
		);
	}

    public function test_name_is_trimmed() : void {

		$user=new \user(
			"    name ",
            new \DateTime("2024-08-24")
		);

		$this->assertEquals("name", $user->get_name());
	}

    public function test_date() : void {

		$this->ExpectException(\Exception::class);
		$this->ExpectExceptionMessage("the date must be realistic");
		$user=new \user(
			"name",
            new \DateTime("1800-08-24")
		);
	}

    public function test_date2() : void {

		$this->ExpectException(\Exception::class);
		$this->ExpectExceptionMessage("the date must be realistic");
		$user=new \user(
			"name",
            new \DateTime("2026-08-24")
		);
	}
}