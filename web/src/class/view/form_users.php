<?php

namespace view;

use \resena\model\user;

class form_users implements app_view_interface
{
    const origin_list_user = "list_user";
    const origin_new_user = "new_user";

    private ?string $insertion_result;
    private ?user $user;
    private string $origin;

    public function __construct(
        ?user $user,
        string $_origin,
        ?string $_insertion_result
    ) {

        $this->user = $user;
        $this->origin = $_origin;
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
                <p>Usuario añadido correctamente</p>
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
            ? "backend/insertar_usuario.php"
            : "backend/modificar_users.php";

        $back_uri = "menu_form.php";
        switch ($this->origin) {
            case self::origin_new_user:
                $back_uri = "menu_form.php";
                break;
            case self::origin_list_user:
                $back_uri = "listado_users.php";
            default:
                //NOOP
                break;
        }

        $user_id = null === $this->user
            ? ""
            : $this->user->get_id();

        return <<<HTML
		{$view_insertion_result}

		<script type="text/javascript" src="assets/js/introducir_usuarios.js"></script>

		<section id="form_user">
			<form action="{$action}" method="POST">
			<label for="nombre">Nombre:</label>
			<input type="text" name="nombre" value="{$name_value}">
			    <label for="fecha">Fecha:</label>
			    <input type="date" name="fecha" value="{$birthdate_value}">
			    <br>
			    <label for="password">Contraseña:</label>
			    <input type="password" name="password">
			    <label for="role">Rol:</label>
			    <select name="role">
                    <option value="">--Elige--</option>
				    <option value="user">Usuario</option>
				    <option value="admin">Administrador</option>
			    </select>
			    <input type="hidden" name="user_id" value="{$user_id}">
			    <button type="button" data-role="send_btn">{$button_text}</button>
			</form>

			<!-- Here be errors -->
			<ul></ul>

            <p data-role="result"></p>

		</section>

                <br>
                <a href="{$back_uri}">Volver</a>
HTML;
    }
}
