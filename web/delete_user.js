function confirm_deletion(user_id) {

    if (!confirm("¿Deseas borrar al usuario?")) {
        return;
    }

    window.location.href =
        "borrar_usuario.php?user_id=" + user_id;
}