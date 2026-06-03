<?php

namespace view;

use \resena\model\user;

class user_list_view implements app_view_interface
{

    public function get_title(): string
    {
        return "Listado de usuarios";
    }

    public function get_main_view(): string
    {
        return <<<HTML
        <script type="text/javascript" src="assets/js/load_users.js"></script>
        <script type="text/javascript" src="assets/js/delete_user.js"></script>

        <table id="user_table">
            <thead>
                <th>ID</th>
                <th>Nombre</th>
                <th>Fecha</th>
                <th>Reseñas</th>
                <th>Modificar</th>
                <th>Borrar</th>
            </thead>
            <tbody>
            </tbody>
        </table>

        <a href="menu.php">Volver</a>

        <template id="user_table_row">
            <tr>
                <td data-content="id"></td>
                <td data-content="name"></td>
                <td data-content="birthdate"></td>
                <td data-content="reviews"></td>
                <td data-content="edit"></td>
                <td data-content="delete"></td>
            </tr>
        </template>
HTML;
    }
}
