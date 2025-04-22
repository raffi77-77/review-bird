<?php

namespace Review_Bird\Includes\Admin;

use Review_Bird\Includes\Review_Bird;
use Review_Bird\Includes\Services\Freemius;
use Review_Bird\Includes\Traits\SingletonTrait;

class Admin {

	use SingletonTrait;

	public $fs;

	public function boot() {
		$this->fs = Freemius::instance()->fs;
		add_action( 'admin_menu', array( $this, 'add_options_page' ) );
		// add_action( 'admin_init', array( Assets::class, 'admin_init' ) );
		// add_action( 'post_updated', array( Vector_Service::class, 'post_object_updated' ), 10, 3 );
		// Ajax::init();
	}

	public function add_options_page(): void {
		add_menu_page( __( 'Review Bird', 'review-bird' ), __( 'Review Bird', 'review-bird' ), 'manage_options', 'review-bird', array( $this, 'page_home' ), '', 13 );
		$settings     = add_submenu_page( 'review-bird', __( 'Settings', 'review-bird' ), __( 'Settings', 'review-bird' ), 'manage_options', 'sr-rb-settings', array( $this, 'page_settings' ), 1 );
		$reviews      = add_submenu_page( 'review-bird', __( 'Reviews', 'review-bird' ), __( 'Reviews', 'review-bird' ), 'manage_options', 'sr-rb-reviews', array( $this, 'page_reviews' ), 3 );
		$skins        = add_submenu_page( 'review-bird', __( 'Skins', 'review-bird' ), __( 'Skins', 'review-bird' ), 'manage_options', 'sr-rb-skins', array( $this, 'page_skins' ), 3 );
		$sms_feedback = add_submenu_page( 'review-bird', __( 'SMS feedback', 'review-bird' ), __( 'SMS feedback', 'review-bird' ), 'manage_options', 'sr-rb-feedback', array( $this, 'page_feedback' ), 3 );
		// add_action( 'admin_print_scripts-' . $settings, array( $this, 'register_settings_scripts' ) );
		// add_action( 'admin_print_styles-' . $settings, array( $this, 'register_settings_styles' ) );
		// add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );
	}

	public function register_settings_scripts(): void {
		$rb = Review_Bird();
		if ( ! wp_script_is( $rb->get_plugin_name() . '-page-settings-script', 'registered' ) ) {
			$settings_script_asset = include( $rb->get_plugin_dir_path() . 'dist/js/admin/something-to-change-page-settings.asset.php' );
			wp_register_script( $rb->get_plugin_name() . '-page-settings-script', $rb->get_plugin_dir_url() . 'dist/js/admin/something-to-change-page-settings.js', $settings_script_asset['dependencies'],
				$settings_script_asset['version'] );
		}
		if ( ! wp_script_is( $rb->get_plugin_name() . '-page-settings-script', 'enqueued' ) ) {
			wp_enqueue_script( $rb->get_plugin_name() . '-page-settings-script' );
			wp_localize_script( $rb->get_plugin_name() . '-page-settings-script', 'Review_Bird', [
				'rest'   => [
					'url'    => get_rest_url( null, 'sr-rb/ai/v1/' ),
					'wp_url' => get_rest_url( null, 'wp/v2/' ),
					'nonce'  => wp_create_nonce( 'wp_rest' )
				],
				'config' => [
					'debug' => true,
				],
			] );
		}
		$chatbot_settings_script_asset = include( $rb->get_plugin_dir_path() . 'dist/js/admin/something-to-change-page-settings-chatbot.asset.php' );
		wp_register_script( $rb->get_plugin_name() . '-page-settings-chatbot-script', $rb->get_plugin_dir_url() . 'dist/js/admin/something-to-change-page-settings-chatbot.js',
			array_merge( $chatbot_settings_script_asset['dependencies'] ?: [], [ $rb->get_plugin_name() . '-page-settings-script' ] ), $chatbot_settings_script_asset['version'] );
		wp_enqueue_script( $rb->get_plugin_name() . '-page-settings-chatbot-script' );
	}

	public function register_settings_styles(): void {
		$rb = Review_Bird();
		if ( ! wp_style_is( $rb->get_plugin_name() . '-page-settings-style', 'registered' ) ) {
			wp_register_style( $rb->get_plugin_name() . '-page-settings-style', $rb->get_plugin_dir_url() . 'dist/css/admin/page/settings.css', array(), $rb->get_version() );
		}
		wp_enqueue_style( $rb->get_plugin_name() . '-page-settings-style' );
		if ( file_exists( $rb->get_plugin_dir_path() . 'dist/js/admin/something-to-change-page-settings.css' ) ) {
			if ( ! wp_style_is( $rb->get_plugin_name() . '-page-settings-js-style', 'registered' ) ) {
				wp_register_style( $rb->get_plugin_name() . '-page-settings-js-style', $rb->get_plugin_dir_url() . 'dist/js/admin/something-to-change-page-settings.css', array(), $rb->get_version() );
			}
			wp_enqueue_style( $rb->get_plugin_name() . '-page-settings-js-style' );
		}
	}

	public function page_reviews(): void {
		require_once Review_Bird::get_instance()->get_plugin_dir_path() . 'includes/admin/class-rb-review-table.php';
		( new Review_Table() )->display();
	}

	public function page_home(): void {
		echo 'Home!';
	}

	public function page_settings(): void {
		// include Review_Bird()->get_plugin_dir_path() . 'templates/.../index.php';
		echo 'Settings!';
	}

	public function page_skins(): void {
		echo 'Skins!';
	}

	public function page_feedback(): void {
		echo 'Feedback!';
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