<?php

namespace Review_Bird\Includes\Admin\Pages\Review;

use Review_Bird\Includes\Review_Bird;

class Page {

	static $menu_slug = 'review-bird-reviews';
	public int $is_single;

	public function __construct() {
		$this->is_single = isset( $_GET['id'] ) ? (int) sanitize_text_field( $_GET['id'] ) : 0;
	}

	public function add_submenu_page() {
		$reviews = add_submenu_page( 'review-bird', __( 'Reviews', 'review-bird' ), __( 'Reviews', 'review-bird' ), 'manage_options', self::$menu_slug, array( $this, 'render' ), 3 );
		// Register single review page styles
		if ( $this->is_single ) {
			add_action( 'admin_print_styles-' . $reviews, array( $this, 'register_review_styles' ) );
		}
	}

	public function render() {
		if ( $this->is_single ) {
			require_once Review_Bird::get_instance()->get_plugin_dir_path() . 'includes/admin/pages/review/class-rb-single.php';
			$single = new Single( $this->is_single );
			$single->display();
		} else {
			require_once Review_Bird::get_instance()->get_plugin_dir_path() . 'includes/admin/pages/review/class-rb-table.php';
			$table = new Table();
			$table->prepare_items();
			$table->display();
		}
	}

	public function register_review_styles() {
		$rb = Review_Bird();
		if ( ! wp_style_is( $rb->get_plugin_name() . '-page-review-style', 'registered' ) ) {
			wp_register_style( $rb->get_plugin_name() . '-page-review-style', $rb->get_plugin_dir_url() . 'dist/css/admin/posts/flow.css', array(), $rb->get_version() );
		}
		wp_enqueue_style( $rb->get_plugin_name() . '-page-review-style' );
	}
}