<?php

namespace view;

use \resena\model\user;
use \session\session_manager;

class menu_form_view implements app_view_interface
{
    public function __construct(
        private session_manager $session_manager,
        private ?user $current_user
    ) {}

    public function get_title(): string
    {
        return "Añadir datos";
    }

    public function get_main_view(): string
    {
        $view = "";

        // Admin
        if (
            null !== $this->current_user
            && $this->current_user->get_role() === 'admin'
        ) {
            $view .= <<<R
        <a href="introducir_libros.php">Añadir libro</a>
        <br>
        <a href="introducir_usuarios.php?origin=new_user">Añadir usuario</a>
        <br>
R;
        }

        $view .= <<<R
        <a href="introducir_resenas.php">Añadir reseña</a>
        <br><br>
        <a href="menu.php">Volver al menú</a>
R;

        return $view;
        R;
    }
}
