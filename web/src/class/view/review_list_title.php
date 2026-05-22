<?php

namespace view;

use \resena\model\review;

class review_list_title implements app_view_interface
{

    /**
     *@param list<review> $reviews
     */
    public function __construct(
        private array $reviews,
        private string $title
    ) {}

    public function get_title(): string
    {
        return "Reseñas de {$this->title}";
    }

    public function get_main_view(): string
    {
        $view_list = "";
        foreach ($this->reviews as $review) {
            $stars = str_repeat("⭐", $review->get_ranking());
            $view_list .=
                <<<R
                <li>
                    ID: {$review->get_id()} |
                    {$review->get_user()}
                    ({$review->get_finished_date()->format("d-m-Y")})
                    : {$stars} | 
                    {$review->get_info()}
                </li>
                R;
        }
        return <<<R
    <ul>
        {$view_list}
    </ul>
    <a href="listado_libros.php">Volver</a>
    R;
    }
}
