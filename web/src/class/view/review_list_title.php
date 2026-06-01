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
        return <<<HTML
        <script type="text/javascript" src="assets/js/load_reviews.js"></script>

        <table id="review_table">
            <thead>
                <th>ID</th>
                <th>Usuario</th>
                <th>Fecha</th>
                <th>Puntuación</th>
                <th>Información</th>
            </thead>
            <tbody>
            </tbody>
        </table>

        <a href="listado_libros.php">Volver</a>
        <template id="review_table_row">
            <tr>
                <td data-content="id"></td>
                <td data-content="user"></td>
                <td data-content="date"></td>
                <td data-content="ranking"></td>
                <td data-content="info"></td>
            </tr>
        </template>
HTML;
    }
}
