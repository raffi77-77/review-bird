<?php

namespace Review_Bird\Includes\Admin;

use Review_Bird\Includes\Admin\Pages\Review;
use Review_Bird\Includes\Admin\Pages\Setting;
use Review_Bird\Includes\Admin\Pages\Skin;
use Review_Bird\Includes\Admin\Pages\Feedback;
use Review_Bird\Includes\Services\Freemius;
use Review_Bird\Includes\Traits\SingletonTrait;

class Admin {

	use SingletonTrait;
	public $fs;
	protected Setting\Page $setting_page;
	protected Review\Page $review_page;
	protected Skin\Page $skin_page;
	protected Feedback\Page $feedback_page;
	
	public function __construct() {
		$this->setting_page = new Setting\Page();
		$this->review_page = new Review\Page();
		$this->skin_page = new Skin\Page();
		$this->feedback_page = new Feedback\Page();
	}

	public function boot() {
		$this->fs = Freemius::instance()->fs;
		add_action( 'admin_menu', array( $this, 'add_options_page' ) );
		// add_action( 'admin_init', array( Assets::class, 'admin_init' ) );
	}

	public function add_options_page(): void {
		add_menu_page( __( 'Review Bird', 'review-bird' ), __( 'Review Bird', 'review-bird' ), 'manage_options', 'review-bird', null, '', 13 );
		$this->setting_page->add_submenu_page();
		$this->review_page->add_submenu_page();
		$this->skin_page->add_submenu_page();
		$this->feedback_page->add_submenu_page();
		// add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );
	}


	
	public function page_home(): void {
		echo 'Home!';
	}

	public function register_admin_scripts( $hook ) {
		global $post_type;
		if ( ( $hook === 'post-new.php' || $hook === 'post.php' ) && $post_type === 'review_bird_flow' ) {
			$rb = Review_Bird();
			// Enqueue single chatbot settings box js
			$settings_script_asset = include( $rb->get_plugin_dir_path() . 'dist/js/admin/something-to-change-single-chatbot.asset.php' );
			wp_register_script( $rb->get_plugin_name() . 'something-if-needed', $rb->get_plugin_dir_url() . 'dist/js/admin/something-if-needed.js', $settings_script_asset['dependencies'],
				$settings_script_asset['version'] );
			wp_enqueue_script( $rb->get_plugin_name() . 'something-if-needed' );
			wp_localize_script( $rb->get_plugin_name() . 'something-if-needed', 'sr_rb', [
				'rest'   => [
					'url'   => get_rest_url( null, 'sr-rb/ai/v1/' ),
					'nonce' => wp_create_nonce( 'wp_rest' )
				],
				'config' => [
					'debug' => true,
				],
				'_post'  => [
					'id' => $hook === 'post.php' ? get_the_ID() : 0,
				],
			] );
			// Enqueue  single chatbot settings box styles
			if ( ! wp_style_is( $rb->get_plugin_name() . '-single-chatbot-style', 'registered' ) ) {
				wp_register_style( $rb->get_plugin_name() . '-single-chatbot-style', $rb->get_plugin_dir_url() . 'dist/css/admin/page/single-chatbot.css', array(), $rb->get_version() );
			}
			wp_enqueue_style( $rb->get_plugin_name() . '-single-chatbot-style' );
		}
	}
}