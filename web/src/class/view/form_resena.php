<?php

namespace view;

class form_resena implements app_view_interface
{
    private ?string $insertion_result;

    public function __construct(
        ?string $_insertion_result
    ) {
        $this->insertion_result = $_insertion_result;
    }

    public function get_title(): string
    {
        return "Añadir reseña";
    }

    public function get_main_view(): string
    {
        $view_insertion_result = "";
        if (null !== $this->insertion_result) {

            $view_insertion_result =
                "ok" === $this->insertion_result
                ? <<<HTML
                <p>Reseña añadida correctamente</p>
                HTML
                : <<<HTML
                <p>Ha ocurrido un error al insertar la reseña</p>
                HTML;
        }

        return <<<R
        {$view_insertion_result}
        <script type="text/javascript" src="assets/js/introducir_resena.js"></script>
        <section id="form_resena">
        <form action="backend/insertar_resena.php" method="POST">
            <label for="titulo">Título:</label><br>
            <input type="text" id="titulo" name="titulo" required><br>
            <label for="usuario">Usuario:</label><br>
            <input type="text" id="usuario" name="usuario" required><br>
            <label for="ranking">Puntuación:</label>
            <select name="ranking" id="ranking">
                <option value="">--Elige--</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select><br>
            <label for="fecha">Fecha de finalización:</label>
            <input type="date" id="fecha" name="fecha" required><br>
            <label for="info">Información de la reseña:</label><br>
            <textarea id="info" name="info" rows="4" cols="50"></textarea><br>
            <button type="button" data-role="send_btn">Añadir</button>
        </form>

        <ul></ul>

        <p data-role="result"></p>

        </section>
        <br>
        <a href="menu_form.php">Volver</a><br>
R;
    }
}
