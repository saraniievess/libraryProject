<?php

namespace view;

use \resena\model\user;

class user_list_view implements app_view_interface
{

    /**
     * @param list<user> $users
     */
    public function __construct(
        private array $users,
        private ?user $logged_user
    ) {}

    public function get_title(): string
    {
        return "Listado de usuarios";
    }

    public function get_main_view(): string
    {
        $view_list = "";
        foreach ($this->users as $user) {
            $review_uri =
                "resenas_user.php?username={$user->get_name()}";
            $edit_form = "";
            $delete_button = "";
            $can_edit = false;
            if ($this->logged_user !== null) {
                if (
                    $this->logged_user->get_role() === 'admin'
                    || $this->logged_user->get_id() === $user->get_id()
                ) {
                    $can_edit = true;
                }
            }

            if (
                $this->logged_user !== null
                && $this->logged_user->get_role() === 'admin'
            ) {
                $delete_button = <<<R
                <button type="button" onclick="confirm_deletion({$user->get_id()})">Borrar</button>
                R;
            }

            if ($can_edit) {
                $edit_form = <<<R
                <a href="editar_usuario.php?user_id={$user->get_id()}">Modificar</a>
                R;
            }

            $view_list .= <<<R
                <li>
                    ID: {$user->get_id()} | {$user->get_name()} | {$user->get_birthdate()->format("d-m-Y")}
                    {$delete_button}
                    {$edit_form}
                    <a href="{$review_uri}">Reseñas</a>
                </li>
            R;
        }

        return <<<R
        <ul>
            {$view_list}
        </ul>
        <script type="text/javascript" src="assets/js/delete_user.js"></script>
        <a href="menu.php">Volver</a>

R;
    }
}
