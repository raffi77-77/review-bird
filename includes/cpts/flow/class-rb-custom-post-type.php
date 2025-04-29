<?php

namespace Review_Bird\Includes\Cpts\Flow;

use Review_Bird\Includes\Data_Objects\Flow_Meta;
use Review_Bird\Includes\Review_Bird;
use Review_Bird\Includes\Services\Helper;
use Review_Bird\Includes\Traits\SingletonTrait;

class Custom_Post_Type {
	use SingletonTrait;

	const NAME = 'review_bird_flow';

	public function __construct() {
		add_filter( 'template_include', array( $this, 'rewrite_template' ) );
		add_action( 'save_post_' . self::NAME, array( $this, 'save_post' ), 10, 3 );
	}
	
	public function save_post($post_id, $post, $update) {
		Flow_Meta::create([ 'post_id' => $post_id, 'meta_key' => '_uuid', 'meta_value' => Helper::get_uuid() ]);
	}

	public function register() {
		register_post_type( self::NAME, array(
			'label'               => __( 'Flows', 'review-bird' ),
			'name'                => self::NAME,
			'labels'              => [
				'name'          => __( 'Flows', 'review-bird' ),
				'singular_name' => __( 'Flow', 'review-bird' ),
				'add_new'       => __( 'Add New', 'review-bird' ),
				'add_new_item'  => __( 'Add new flow', 'review-bird' ),
				'edit_item'     => __( 'Edit flow', 'review-bird' ),
				'new_item'      => __( 'New flow', 'review-bird' ),
			],
			'supports'            => array( 'title', 'editor', 'thumbnail'/*, 'custom-fields'*/ ),
			'description'         => __( '', 'review-bird' ),
			'public'              => true,
			'show_ui'             => true,
			'map_meta_cap'        => true,
			'publicly_queryable'  => true,
			'exclude_from_search' => true,
			'show_in_menu'        => 'review-bird',
			'hierarchical'        => false,
			'show_in_nav_menus'   => false,
			'rewrite'             => [ 'slug' => 'review' ],
			'query_var'           => true,
			'has_archive'         => true,
			'show_in_rest'        => false,
		) );
		$this->add_meta_boxes();
	}

	public function rewrite_template( $template ) {
		if ( is_singular( self::NAME ) ) {
			return $this->load_singular_template( $template );
		}
		if ( is_post_type_archive( self::NAME ) ) {
			return $this->load_archive_template( $template );
		}

		return $template;
	}

	protected function load_singular_template( $template ) {
		$plugin_template = Review_Bird::get_instance()->get_plugin_dir_path() . 'includes/cpts/flow/templates/single.php';
		if ( file_exists( $plugin_template ) ) {
			return $plugin_template;
		}

		return $template;
	}

	protected function load_archive_template( $template ) {
		$plugin_archive_template = plugin_dir_path( __FILE__ ) . 'templates/archive-your_cpt_slug.php';
		if ( file_exists( $plugin_archive_template ) ) {
			return $plugin_archive_template;
		}

		return $template;
	}

	protected function add_meta_boxes() {
		add_action( 'add_meta_boxes', array( $this, 'title_question_meta_box' ) );
		add_action( 'add_meta_boxes', array( $this, 'positive_review_response_meta_box' ) );
		add_action( 'add_meta_boxes', array( $this, 'negative_review_response_meta_box' ) );
		add_action( 'add_meta_boxes', array( $this, 'email_settings_meta_box' ) );
	}

	public function title_question_meta_box() {
		add_meta_box( 'title-question', __( 'Title Question', 'review-bird' ), function () {
			echo 'Title question !';
		}, self::NAME, 'normal', 'high' );
	}

	public function positive_review_response_meta_box() {
		add_meta_box( 'positive-review-response', __( 'Positive Review Response', 'review-bird' ), function () {
			echo 'Positive Review Response !';
		}, self::NAME, 'normal', 'high' );
	}

	public function negative_review_response_meta_box() {
		add_meta_box( 'negative-review-response', __( 'Negative Review Response', 'review-bird' ), function () {
			echo 'Negative Review Response !';
		}, self::NAME, 'normal', 'high' );
	}

	public function email_settings_meta_box() {
		add_meta_box( 'email-settings', __( 'E-Mail sent on negative response', 'review-bird' ), function () {
			echo 'E-Mail sent on negative response !';
		}, self::NAME, 'normal', 'high' );
	}
}