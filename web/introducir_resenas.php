<?php

declare(strict_types=1);

use \app\config_factory;
use \app\pdo_factory;
use \view\html_view;
use \view\form_resena;
use \resena\database\review_repository;
use \resena\model\review;

require_once("src/autoload.php");
require_once("auth.php");

require_login();

if (is_visitor()) {
    header('Location: index.php');
    exit(0);
}

if (!can_add_reviews()) {
    die('Acceso denegado');
}

$config_factory = new config_factory();
$pdo_factory = new pdo_factory();
$database_connection = $pdo_factory->create($config_factory->create_production());

$review_repository = new review_repository($database_connection);

$form_resena = new form_resena();
$view = new html_view();
echo $view->create($form_resena);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['titulo'])) {
    $titulo = trim($_GET['titulo']);
    $usuario = trim($_POST['usuario']);
    $fecha = new DateTime($_POST['fecha']);
    $ranking = (int)$_POST['ranking'];
    $info = trim($_POST['info']);

    $review = new review(
        $titulo,
        $usuario,
        $ranking,
        $fecha,
        $info
    );

    $review_repository->insert($review);

    echo "Reseña añadida correctamente";
}

exit(0);
