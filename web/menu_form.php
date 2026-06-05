<?php

declare(strict_types=1);

session_start();

require_once("src/autoload.php");

use \app\service_provider;
use \view\html_view;
use \view\menu_form_view;

$service_provider = new service_provider();
$database_connection = $service_provider->get_database_connection();
$session_manager = $service_provider->get_session_manager();
$current_user = $session_manager->get_logged_in_user();
$session_manager->commit_last_activity();

$menu_form_view = new menu_form_view($current_user);
$view = new html_view();
echo $view->create($menu_form_view);

exit(0);
