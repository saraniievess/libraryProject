<?php

namespace view;

use \resena\model\user;

class user_list_view implements app_view_interface
{

    /**
     *@param list<user> $users
     */
    public function __construct(
        private array $users
    ) {}

    public function get_title(): string
    {

        return "Listado de usuarios";
    }

    public function get_main_view(): string
    {

        $view_list = "";
        foreach ($this->users as $user) {

            $review_uri = "resenas_user.php?user_id={$user->get_id()}";
            $view_list .= <<<R
            <li> 
                ID: {$user->get_id()} | {$user->get_name()} | {$user->get_birthdate()->format("d-m-Y")} 
                <form action="editar_usuario.php" method="POST" style="display:inline;">
                    <input type="hidden" name="user_id" value="{$user->get_id()}">
                    <button type="submit">Modificar</button>
                </form>
                <a href="{$review_uri}">Reseñas</a>
            </li>
            R;
        }
        return <<<R
	<ul>
		{$view_list}
	</ul>
	<a href="menu.html">Volver</a>
	R;
    }
}
