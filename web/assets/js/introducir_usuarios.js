document.addEventListener(
	"readystatechange",
	(event) => {

		if (document.readyState !== "complete") {

			return;
		}

		let dom = document.getElementById("form_user");
		let user_interface = new ui_form_users(dom);
	},
	true
);

class ui_form_users {

	constructor(_dom_context) {

		this.dom_context = _dom_context;
		this.submit_button = _dom_context.querySelector("form button[data-role='send_btn']");
		this.error_list = _dom_context.querySelector("ul");
		this.form = _dom_context.querySelector("form");
		this.result = _dom_context.querySelector("[data-role='result']");

		this.setup_events();
	}

	setup_events() {

		this.submit_button.addEventListener("click", () => { this.attempt_to_send_form(); }, true);
	}

	attempt_to_send_form() {
		this.result.innerText = "";
		if (!this.validate_form()) {
			return;
		}
		let payload =
			new FormData(this.form);
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
						"Usuario añadido correctamente";
					this.form.reset();
				}
				else {
					this.result.innerText =
						"Ha ocurrido un error";
				}
			});
	}

	validate_form() {

		let errors = [];

		if (0 === this.form.nombre.value.trim().length) {
			errors.push("El nombre está vacío");
		}

		if (0 === this.form.fecha.value.trim().length) {
			errors.push("La fecha está vacía");
		}

		if (this.form.action.includes("insertar_usuario.php")
			&& 0 === this.form.password.value.trim().length
		) {
			errors.push("La contraseña está vacía");
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

