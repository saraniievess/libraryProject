document.addEventListener(
    "readystatechange",
    (event) => {
        if (
            document.readyState !== "complete"
        ) {
            return;
        }

        const table = document.getElementById("user_table");
        const template = document.getElementById("user_table_row");
        let ui = new user_ui(table, template);

        ui.init();
    },
    true
);

class user_ui {
    constructor(
        _table,
        _template
    ) {
        this.table = _table;
        this.template = _template;
        this.current_user = null;
        this.users = [];
    }

    init() {
        this.load_me()
            .then(
                () => {
                    return this.load_users();
                }
            )
            .then(
                () => {
                    this.draw_users(this.users
                    );
                }
            );
    }

    load_me() {
        return fetch(
            "backend/me.php",
            {
                method: "GET"
            }
        )
            .then(
                (_res) => {
                    return _res.json();
                }
            )
            .then(
                (_res) => {
                    this.current_user = _res;
                }
            );
    }

    load_users() {
        return fetch(
            "backend/get_users.php",
            {
                method: "GET"
            }
        )
            .then(
                (_res) => {
                    return _res.json();
                }
            )
            .then(
                (_res) => {
                    this.users = _res.data;
                }
            );
    }

    draw_users(
        _users
    ) {
        let tbody = this.table.querySelector("tbody");
        tbody.innerHTML = "";

        _users.forEach(
            (_user) => {
                this.draw_user(_user, tbody);
            }
        );
    }

    draw_user(_user, _tbody
    ) {

        let clone = document.importNode(this.template.content, true);

        clone.querySelector("[data-content='id']").innerText = _user.id;
        clone.querySelector("[data-content='name']").innerText = _user.name;
        clone.querySelector("[data-content='birthdate']").innerText = _user.birthdate;

        let reviews_cell = clone.querySelector("[data-content='reviews']");
        let reviews_link = document.createElement("a");

        reviews_link.href = "resenas_user.php?username=" + encodeURIComponent(_user.name);
        reviews_link.innerText = "Reseñas";
        reviews_cell.appendChild(reviews_link);

        let can_edit = false;

        if (
            this.current_user.logged_in
        ) {
            if (
                this.current_user.role === "admin"
                || this.current_user.user_id === _user.id
            ) {
                can_edit = true;
            }
        }

        if (can_edit) {
            let edit_cell = clone.querySelector("[data-content='edit']");
            let edit_link = document.createElement("a");

            edit_link.href = "editar_usuario.php?user_id=" + _user.id;
            edit_link.innerText = "Modificar";
            edit_cell.appendChild(edit_link);
        }

        if (
            this.current_user.logged_in &&
            this.current_user.role === "admin"
        ) {
            let delete_cell = clone.querySelector("[data-content='delete']");
            let delete_button = document.createElement("button");

            delete_button.type = "button";
            delete_button.innerText = "Borrar";
            delete_button.onclick =
                () => {
                    confirm_deletion(_user.id);
                };

            delete_cell.appendChild(delete_button);
        }

        _tbody.appendChild(clone);
    }
}