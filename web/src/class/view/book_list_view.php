<?php

namespace view;

use \catalogo\model\book;

class book_list_view implements app_view_interface
{
	public function get_title(): string
	{
		return "Listado de libros";
	}

	public function get_main_view(): string
	{
		return <<<R
		<script type="text/javascript" src="assets/js/load_books.js"></script>

		<table id="book_table">
			<thead>
				<th>ID</th>
				<th>Título</th>
				<th>Autor</th>
				<th>Editorial</th>
				<th>Páginas</th>
				<th>Género</th>
				<th>Reseñas</th>
			</thead>
			<tbody></tbody>
			<tfoot></tfoot>
		</table>

		<a href="menu.php">Volver</a>

		<template id="book_table_row">
			<tr>
				<td data-content="id"></td>
				<td data-content="title"></td>
				<td data-content="author"></td>
				<td data-content="house"></td>
				<td data-content="page_count"></td>
				<td data-content="genre"></td>
				<td data-content="reviews"></td>
			</tr>
		</template>
R;
	}
}
