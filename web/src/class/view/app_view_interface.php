<?php

namespace view;

interface app_view_interface
{
	public function get_title(): string;
	public function get_main_view(): string;
}
