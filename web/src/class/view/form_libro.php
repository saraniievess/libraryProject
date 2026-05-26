<?php

namespace view;

class form_libro implements app_view_interface
{
    private ?string $insertion_result;

    public function __construct(
        ?string $_insertion_result
    ) {
        $this->insertion_result = $_insertion_result;
    }

    public function get_title(): string
    {
        return "Añadir libro";
    }

    public function get_main_view(): string
    {

        $view_insertion_result = "";

        if (null !== $this->insertion_result) {

            $view_insertion_result =
                "ok" === $this->insertion_result
                ? <<<HTML
                <p>Libro añadido correctamente</p>
                HTML
                : <<<HTML
                <p>Ha ocurrido un error al insertar el libro</p>
                HTML;
        }

        return <<<R
        {$view_insertion_result}
        <script type="text/javascript" src="assets/js/introducir_libro.js"></script>
        <section id="form_libros">
        <form action="backend/insertar_libro.php" method="POST">
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
            <button type="button" data-role="send_btn">Añadir</button>
        </form>

        <ul></ul>

        </section>
        <br>
        <a href="menu_form.php">Volver</a><br>
        R;
    }
}
