document.addEventListener(
    "readystatechange",
    (event) => {
        if (document.readyState !== "complete") {
            return;
        }
        let dom = document.getElementById("form_libros");
        let libro_interface = new ui_form_libro(dom);
    },
    true
);

class ui_form_libro {

    constructor(_dom_context) {
        this.dom_context = _dom_context;
        this.submit_button = _dom_context.querySelector("form button[data-role='send_btn']");
        this.error_list = _dom_context.querySelector("ul");
        this.form = _dom_context.querySelector("form");
        this.setup_events();
        this.result = _dom_context.querySelector("[data-role='result']");
    }

    setup_events() {
        this.submit_button.addEventListener("click", () => { this.attempt_to_send_form(); }, true);
    }

    attempt_to_send_form() {
        if (!this.validate_form()) {
            return;
        }
        let payload = new FormData(this.form);
        fetch(
            this.form.action,
            {
                method: "POST",
                body: payload
            }
        )
            .then((_result) => {
                return _result.json();
            })
            .then((_json) => {
                if (_json.status === "ok") {
                    this.result.innerText =
                        "Libro añadido correctamente";
                }
                else {
                    this.result.innerText =
                        "Ha ocurrido un error";
                }
            });
    }

    validate_form() {
        let errors = [];

        if (0 === this.form.titulo.value.trim().length) {
            errors.push("El título está vacío");
        }

        if (0 === this.form.autor.value.trim().length) {
            errors.push("El autor está vacío");
        }

        if (0 === this.form.editorial.value.trim().length) {
            errors.push("La editorial está vacía");
        }

        if (0 === this.form.genero.value.trim().length) {
            errors.push("El género está vacío");
        }

        if (0 === this.form.pag_total.value.trim().length) {
            errors.push("Las páginas están vacías");
        } else if (isNaN(this.form.pag_total.value)) {
            errors.push("El número de páginas debe ser un número");
        }

        this.error_list.innerHTML = "";
        if (errors.length !== 0) {
            errors.forEach((_err) => {
                let li = document.createElement("li");
                li.innerText = _err;
                this.error_list.appendChild(li);
            });
            return false;
        }
        return true;
    }
}

