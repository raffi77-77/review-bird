<?php

namespace Review_Bird\Includes\Admin\Pages\Feedback;

class Page {

	static $menu_slug = 'sr-rb-feedback';
	public function __construct() {}
	
	public function add_submenu_page() {
		$sms_feedback = add_submenu_page( 'review-bird', __( 'SMS feedback', 'review-bird' ), __( 'SMS feedback', 'review-bird' ), 'manage_options', self::$menu_slug, array( $this, 'render' ), 3 );
	}
	
	public function render() {
		// include Review_Bird()->get_plugin_dir_path() . 'templates/.../index.php';
		echo 'Feedback!';
	}
}