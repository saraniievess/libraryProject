<?php

namespace view;

ini_set('display_errors', '1');
ini_set('error_reporting', '-1');

use \resena\model\review;

class menu_view implements app_view_interface
{
    /**
     *@param list<review> $reviews
     */
    public function __construct(
        private array $reviews,
        private string $name
    ) {}

    public function get_title(): string
    {
        return "Reseñas de {$this->name}";
    }

    public function get_main_view(): string {}
}
