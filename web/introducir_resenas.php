<?php

declare(strict_types=1);

use \app\config_factory;
use \app\pdo_factory;
use \view\html_view;
use \view\form_resena;
use \resena\database\review_repository;
use \resena\model\review;

require_once("src/autoload.php");

$config_factory = new config_factory();
$pdo_factory = new pdo_factory();
$database_connection = $pdo_factory->create($config_factory->create_production());

$review_repository = new review_repository($database_connection);

$form_resena = new form_resena();
$view = new html_view();
echo $view->create($form_resena);

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['titulo'])) {
    $titulo = trim($_GET['titulo']);
    $usuario = trim($_GET['usuario']);
    $fecha = new DateTime($_GET['fecha']);
    $ranking = (int)$_GET['ranking'];
    $info = trim($_GET['info']);

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
