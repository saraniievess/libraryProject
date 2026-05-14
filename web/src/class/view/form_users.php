<?php

namespace view;

class form_users implements app_view_interface
{
    public function get_title(): string
    {
        return "Añadir usuario";
    }

    public function get_main_view(): string
    {
        return <<<R
        <form>
            <label for="nombre">Nombre:</label><br>
            <input type="text" id="nombre" name="nombre" required><br>
            <label for="fecha">Fecha de finalización:</label>
            <input type="date" id="fecha" name="fecha" required><br>
            <input type="submit" value="Añadir">
        </form>
        <br>
        <a href="index_form.html">Volver</a><br>
        R;
    }
}
