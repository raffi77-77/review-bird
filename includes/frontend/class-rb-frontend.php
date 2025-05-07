<?php

namespace Review_Bird\Includes\frontend;

use Review_Bird\Includes\Traits\SingletonTrait;

class Frontend {

	use SingletonTrait;

	public function init() {
		add_shortcode( 'sr_rb_shortcode', array( $this, 'shortcode' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_flow_block_js' ) );
	}

	public function openai_output_shortcode() {
		echo 'RB shortcode';
	}

	public function enqueue() {
		// wp_enqueue_script( 'open-ai-streaming-js', plugins_url( '/open-ai-streaming.js', __FILE__ ), array(), null, true );
	}

	public function enqueue_flow_block_js() {
		$rb = Review_Bird();
//		$hooks_script_asset = include( $rb->get_plugin_dir_path() . 'dist/js/public/something-to-change.asset.php' );
//		wp_register_script( $rb->get_plugin_name() . '-something-to-change', $rb->get_plugin_dir_url() . 'dist/js/public/something-to-change.js', $hooks_script_asset['dependencies'], $hooks_script_asset['version'] );
		wp_localize_script( $rb->get_plugin_name() . '-flow-view-script', 'ReviewBird', array(
			'rest' => array(
				'url'   => get_rest_url( null, 'review-bird/v1/' ),
				'nonce' => wp_create_nonce( 'wp_rest' ),
			),
//			'debug' => true,
		) );
//		wp_enqueue_script( $rb->get_plugin_name() . '-something-to-change' );
//		wp_register_style( $rb->get_plugin_name() . '-something-to-change', $rb->get_plugin_dir_url() . 'dist/css/something-to-change/main.css', array(), $rb->get_version() );
//		wp_enqueue_style( $rb->get_plugin_name() . '-something-to-change' );
	}
}
