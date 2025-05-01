<?php

namespace Review_Bird\Includes\Admin\Pages\Skin;

class Page {

	static $menu_slug = 'sr-rb-skins';
	public function __construct() {}

	public function add_submenu_page() {
		$skins = add_submenu_page( 'review-bird', __( 'Skins', 'review-bird' ), __( 'Skins', 'review-bird' ), 'manage_options', self::$menu_slug, array( $this, 'render' ), 3 );
		add_action( 'admin_print_styles-' . $skins, array( $this, 'register_settings_styles' ) );
	}

	public function render() {
		// include Review_Bird()->get_plugin_dir_path() . 'templates/.../index.php';
		echo 'Skin!';
	}

	public function register_settings_styles(): void {
		$rb = Review_Bird();
		if ( ! wp_style_is( $rb->get_plugin_name() . '-page-skin-style', 'registered' ) ) {
			wp_register_style( $rb->get_plugin_name() . '-page-skin-style', $rb->get_plugin_dir_url() . 'dist/css/admin/pages/skin.css', array(), $rb->get_version() );
		}
		wp_enqueue_style( $rb->get_plugin_name() . '-page-skin-style' );
	}
}

