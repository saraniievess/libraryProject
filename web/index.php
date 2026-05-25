<?php

declare(strict_types=1);

session_start();

use \view\html_view;
use \view\login_view;

require_once("src/autoload.php");

$error = $_GET['error'] ?? "";
$login_view = new login_view($error);
$view = new html_view();
echo $view->create($login_view);

exit(0);
