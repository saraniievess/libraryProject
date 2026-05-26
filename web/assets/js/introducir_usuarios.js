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

		this.setup_events();
	}

	setup_events() {

		this.submit_button.addEventListener("click", () => { this.attempt_to_send_form(); }, true);
	}

	attempt_to_send_form() {

		if (!this.validate_form()) {
			return;
		}

		this.form.submit();
	}

	validate_form() {

		let errors = [];

		if (0 === this.form.nombre.value.trim().length) {
			errors.push("El nombre está vacío");
		}

		if (0 === this.form.fecha.value.trim().length) {
			errors.push("La fecha está vacía");
		}

		if (0 === this.form.password.value.trim().length) {
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

