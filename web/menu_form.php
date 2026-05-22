<?php

declare(strict_types=1);

session_start();

require_once("src/autoload.php");

use \app\config_factory;
use \app\pdo_factory;
use \session\session_manager;
use \view\html_view;
use \view\menu_form_view;

$config_factory = new config_factory();
$pdo_factory = new pdo_factory();
$database_connection = $pdo_factory->create($config_factory->create_production());
$session_manager = new session_manager($database_connection, session_id());
$current_user = $session_manager->get_logged_in_user();
$session_manager->commit_last_activity();

$menu_form_view = new menu_form_view($session_manager, $current_user);
$view = new html_view();
echo $view->create($menu_form_view);

exit(0);
