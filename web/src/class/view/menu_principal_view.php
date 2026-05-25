<?php

namespace view;

use \session\session_manager;

class menu_principal_view implements app_view_interface
{
    public function __construct(
        private session_manager $session_manager
    ) {}

    public function get_title(): string
    {
        return "Menú";
    }

    public function get_main_view(): string
    {
        $session_link = "";
        if ($this->session_manager->is_visitor()) {
            $session_link = <<<R
            <a href="index.php">Iniciar sesión</a>
            R;
        } else {
            $session_link = <<<R
            <a href="backend/logout.php">Cerrar sesión</a>
            R;
        }
        return <<<R
        <a href="listado_libros.php">Listar libros</a>
        <br>
        <a href="listado_users.php">Listar usuarios</a>
        <br>
        <a href="menu_form.php">Añadir datos</a>
        <br><br>
        {$session_link}
R;
    }
}
