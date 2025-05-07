<?php

namespace Review_Bird\Includes\Admin\Pages\Feedback;

class Page {

	static $menu_slug = 'sr-rb-feedback';
	public function __construct() {}
	
	public function add_submenu_page() {
		$sms_feedback = add_submenu_page( 'review-bird', __( 'SMS feedback', 'review-bird' ), __( 'SMS feedback', 'review-bird' ), 'manage_options', self::$menu_slug, array( $this, 'render' ), 3 );
		add_action( 'admin_print_styles-' . $sms_feedback, array( $this, 'register_settings_styles' ) );
	}
	
	public function render() {
		 include Review_Bird()->get_plugin_dir_path() . 'templates/admin/pages/feedback.php';
	}

	public function register_settings_styles(): void {
		$rb = Review_Bird();
		if ( ! wp_style_is( $rb->get_plugin_name() . '-page-feedback-style', 'registered' ) ) {
			wp_register_style( $rb->get_plugin_name() . '-page-feedback-style', $rb->get_plugin_dir_url() . 'dist/css/admin/pages/feedback.css', array(), $rb->get_version() );
		}
		wp_enqueue_style( $rb->get_plugin_name() . '-page-feedback-style' );
	}
}