<?php

namespace view;

class form_resena implements app_view_interface
{
    public function get_title(): string
    {
        return "Añadir reseña";
    }

    public function get_main_view(): string
    {
        return <<<R
        <form action="introducir_resenas.php" method="POST">
            <label for="titulo">Título:</label><br>
            <input type="text" id="titulo" name="titulo" required><br>
            <label for="usuario">Usuario:</label><br>
            <input type="text" id="usuario" name="usuario" required><br>
            <label for="ranking">Puntuación:</label>
            <select name="ranking" id="ranking">
                <option value="">--Elige--</option>
                <option value=1>1</option>
                <option value=1>2</option>
                <option value=2>3</option>
                <option value=4>4</option>
                <option value=5>5</option>
            </select><br>
            <label for="fecha">Fecha de finalización:</label>
            <input type="date" id="fecha" name="fecha" required><br>
            <label for="info">Información de la reseña:</label><br>
            <textarea id="info" name="info" rows="4" cols="50"></textarea><br>
            <input type="submit" value="Añadir">
        </form>
        <br>
        <a href="index_form.php">Volver</a><br>
        R;
    }
}
