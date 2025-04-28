<?php

namespace Review_Bird\Includes\Admin\Pages\Skin;

class Page {

	static $menu_slug = 'sr-rb-skins';
	public function __construct() {}
	
	public function add_submenu_page() {
		$skins        = add_submenu_page( 'review-bird', __( 'Skins', 'review-bird' ), __( 'Skins', 'review-bird' ), 'manage_options', self::$menu_slug, array( $this, 'render' ), 3 );
	}

	public function render() {
		// include Review_Bird()->get_plugin_dir_path() . 'templates/.../index.php';
		echo 'Skin!';
	}
}

