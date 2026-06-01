<?php

namespace view;

ini_set('display_errors', '1');
ini_set('error_reporting', '-1');

use \resena\model\review;

class review_list_name implements app_view_interface
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

    public function get_main_view(): string
    {
        return <<<HTML
        <script type="text/javascript" src="assets/js/load_reviews_user.js"></script>

        <table id="review_user_table">
            <thead>
                <th>ID</th>
                <th>Título</th>
                <th>Fecha</th>
                <th>Puntuación</th>
                <th>Información</th>
            </thead>
            <tbody>
            </tbody>
        </table>

        <a href="listado_users.php">Volver</a>

        <template id="review_user_table_row">
            <tr>
                <td data-content="id"></td>
                <td data-content="title"></td>
                <td data-content="date"></td>
                <td data-content="ranking"></td>
                <td data-content="info"></td>
            </tr>
        </template>
HTML;
    }
}
