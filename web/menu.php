<?php

declare(strict_types=1);

session_start();

use \app\service_provider;
use \view\html_view;
use \view\menu_principal_view;

require_once("src/autoload.php");

$service_provider = new service_provider();
$database_connection = $service_provider->get_database_connection();
$session_manager = $service_provider->get_session_manager();
$current_user = $session_manager->get_logged_in_user();
$session_manager->commit_last_activity();

$menu_principal_view = new menu_principal_view($session_manager);
$view = new html_view();
echo $view->create($menu_principal_view);

exit(0);
