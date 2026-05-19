<?php

namespace view;

use \resena\model\user;

class form_users implements app_view_interface
{

    private ?string $insertion_result;
    private ?user $user;

    public function __construct(?user $user, ?string $_insertion_result)
    {
        $this->user = $user;
        $this->insertion_result = $_insertion_result;
    }

    public function get_title(): string
    {
        if ($this->user === null) {
            return "Añadir usuario";
        } else {
            return "Modificar usuario";
        }
    }

    public function get_main_view(): string
    {
        $view_insertion_result = "";
        if (null !== $this->insertion_result) {
            $view_insertion_result = "ok" === $this->insertion_result
                ? <<< R
            <p>Usuario anadido correctamente</p>
            R
                : <<< R
            <p>Ha ocurrido un error al insertar el usuario</p>
            R;
        }
        $name_value = null === $this->user
            ? ""
            : $this->user->get_name();

        $birthdate_value = null === $this->user
            ? ""
            : $this->user->get_birthdate()->format("Y-m-d");

        $button_text = $this->user === null
            ? "Añadir usuario"
            : "Modificar usuario";

        $action = $this->user === null
            ? "introducir_usuarios.php"
            : "modificar_users.php";

        return <<<HTML
            $view_insertion_result

            <form action="$action" method="POST">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" value="$name_value">
                <label for="fecha">Fecha:</label>
                <input type="date" id="fecha" name="fecha" value="$birthdate_value">
                <br>
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password">
                <label for="role">Rol:</label>
                <select name="role" id="role">
                    <option value="user">Usuario</option>
                    <option value="admin">Administrador</option>
                </select>
                <input type="hidden" name="user_id" value="{$this->user?->get_id()}">
                <button type="submit">$button_text</button>
            </form>
            <br>
            <a href="menu.php">Inicio</a>
            HTML;
    }
}
