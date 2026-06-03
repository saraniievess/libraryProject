function confirm_deletion(user_id) {

    if (!confirm("¿Deseas borrar al usuario?")) {
        return;
    }

    fetch(
        "backend/borrar_usuario.php?user_id=" + user_id,
        {
            method: "DELETE"
        }
    )
        .then((_result) => {
            return _result.json();
        })
        .then((_json) => {

            if (_json.status === "ok") {

                location.reload();
            }
        });
}