<?php

namespace view;

class form_libro implements app_view_interface
{
    public function get_title(): string
    {
        return "Añadir libro";
    }

    public function get_main_view(): string
    {
        return <<<R
        <form action="introducir_libros.php" method="POST">
            <label for="titulo">Título:</label><br>
            <input type="text" id="titulo" name="titulo" required><br>
            <label for="autor">Autor:</label><br>
            <input type="text" id="autor" name="autor" required><br>
            <label for="editorial">Editorial:</label><br>
            <input type="text" id="editorial" name="editorial" required><br>
            <label for="genero">Género:</label><br>
            <input type="text" id="genero" name="genero" required><br>
            <label for="pag_total">Páginas totales:</label><br>
            <input type="text" id="pag_total" name="pag_total" required><br>
            <input type="submit" value="Añadir">
        </form>
        <br>
        <a href="index_form.html">Volver</a><br>
        R;
    }
}
