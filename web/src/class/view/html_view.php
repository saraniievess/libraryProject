<?php

namespace view;

class html_view
{

	public function create(
		app_view_interface $_view
	) {

		$title = $_view->get_title();
		$main_view = $_view->get_main_view();

		return <<<R
			<!DOCTYPE html>
			<html lang="es">
			<head>
				<meta charset="UTF-8">
				<meta name="viewport" content="width=device-width, initial-scale=1.0">
				<link rel="icon" type="image/png" href="/home/sara/sara/libraryProject/web/img/logo.png">
				<title>{$title}</title>
			</head>
			<body>
				<h1>{$title}</h1>

				{$main_view}
			</body>
			</html>
			R;
	}
}
