<?php

namespace view;

class login_view implements app_view_interface
{

    public function __construct(
        private string $error
    ) {}

    public function get_title(): string
    {
        return "Login";
    }

    public function get_main_view(): string
    {
        $view = "";
        $view .= <<<R
        <p>{$this->error}</p>
        <form method="POST">
            <label for="name">Nombre:</label>
            <input type="text" name="name" id="name">
            <br>
            <label for="password">Contraseña:</label>
            <input type="password" name="password" id="password">
            <br>
            <button type="submit">Iniciar sesión</button>
        </form>
        <br>
        <form method="POST">
            <button type="submit" name="visitor">Continuar como visitante</button>
        </form>
        </body>
R;
        return $view;
    }
}
