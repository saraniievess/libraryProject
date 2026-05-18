<?php

namespace view;

use \catalogo\model\book;

class book_list_view implements app_view_interface
{

	/**
	 *@param list<book> $books
	 */
	public function __construct(
		private array $books
	) {}

	public function get_title(): string
	{

		return "Listado de libros";
	}

	public function get_main_view(): string
	{

		$view_list = "";
		foreach ($this->books as $book) {

			$review_uri = "resenas_libro.php?book_id={$book->get_id()}";
			$view_list .= <<<R

		<li> ID: {$book->get_id()} | {$book->get_title()} by {$book->get_author()} published by {$book->get_house()}. Pages: {$book->get_page_count()}. Genre: {$book->get_genre()} <a href="{$review_uri}">Reseñas</a></li>
		R;
		}

		return <<<R

	<ul>
		{$view_list}
	</ul>
	<a href="menu.html">Volver</a>
	R;
	}
}
