<?php

namespace view;

class html_view
{

	public function create(app_view_interface $_view)
	{

		$title = $_view->get_title();
		$main_view = $_view->get_main_view();

		return <<<R
			<!DOCTYPE html>
			<html lang="es">
			<head>
				<meta charset="UTF-8">
				<meta name="viewport" content="width=device-width, initial-scale=1.0">
				<link rel="icon" type="image/png" href="assets/img/logo.png">
				<link rel="stylesheet" type="text/css" media="screen" href="assets/css/css.css" />
				<title>{$title}</title>
					
			</head>
			<body>

				<h1>{$title}</h1>
				<img src="assets/img/logo.png" class="logo" /><br>

				{$main_view}
			</body>
			</html>
R;
	}
}
