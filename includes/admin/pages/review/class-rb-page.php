<?php

namespace Review_Bird\Includes\Admin\Pages\Review;

use Review_Bird\Includes\Review_Bird;

class Page {

	static $menu_slug = 'sr-rb-reviews';
	public function __construct() {}
	
	public function add_submenu_page() {
		$reviews      = add_submenu_page( 'review-bird', __( 'Reviews', 'review-bird' ), __( 'Reviews', 'review-bird' ), 'manage_options', self::$menu_slug, array( $this, 'render' ), 3 );
	}
	
	public function render() {
		require_once Review_Bird::get_instance()->get_plugin_dir_path() . 'includes/admin/pages/review/class-rb-table.php';
		$table = new Table();
		$table->prepare_items();
		$table->display();
	}
}