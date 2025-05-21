<?php

namespace Review_Bird\Includes\Cpts\Flow;

use Exception;
use Review_Bird\Includes\Data_Objects\Setting;
use Review_Bird\Includes\Repositories\Flow_Repository;
use Review_Bird\Includes\Review_Bird;
use Review_Bird\Includes\Services\Helper;
use Review_Bird\Includes\Services\Sanitizer;
use Review_Bird\Includes\Services\Validator;
use Review_Bird\Includes\Traits\SingletonTrait;

class Custom_Post_Type {
	use SingletonTrait;

	const NAME = 'review_bird_flow';
	protected Flow_Repository $repository;
	protected Meta_Scheme $meta_scheme;

	public function __construct() {
		$this->repository  = new Flow_Repository();
		$this->meta_scheme = new Meta_Scheme();
		add_filter( 'template_include', array( $this, 'rewrite_template' ) );
		add_action( 'save_post_' . self::NAME, array( $this, 'save_post' ), 10, 3 );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	public function save_post( $post_id, $post, $update ) {
		try {
			if ( $metas = $_POST['metas'] ?? null ) {
				$sanitizer = new Sanitizer( $this->meta_scheme );
				$validator = new Validator( $this->meta_scheme );
				$sanitizer->sanitize( $metas );
				if ( ! $validator->validate( $sanitizer->get_sanitized() ) ) {
					Helper::log($validator->get_errors(), 'cpt save error');
					return;
				}
				$flow = $this->repository->save_on_hook($post_id, $post, [ 'metas' => $validator->get_validated() ]);
			}
		} catch ( Exception $exception ) {
			Helper::log( $exception, __( 'Failed to save chatbot.', 'review-bird' ) );
		}
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
			'rewrite'             => [ 'slug' => Setting::find(Review_Bird::get_instance()->get_plugin_prefix() . '_utilities_flow_flow_slug')->get_value() ?? 'review' ],
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
			ob_start();
			include Review_Bird()->get_plugin_dir_path() . 'templates/admin/posts/meta-boxes/title-questions.php';
			echo ob_get_clean();
		}, self::NAME, 'normal', 'high' );
	}

	public function positive_review_response_meta_box() {
		add_meta_box( 'positive-review-response', __( 'Positive Review Response', 'review-bird' ), function () {
			ob_start();
			include Review_Bird()->get_plugin_dir_path() . 'templates/admin/posts/meta-boxes/positive-review.php';
			echo ob_get_clean();
		}, self::NAME, 'normal', 'high' );
	}

	public function negative_review_response_meta_box() {
		add_meta_box( 'negative-review-response', __( 'Negative Review Response', 'review-bird' ), function () {
			ob_start();
			include Review_Bird()->get_plugin_dir_path() . 'templates/admin/posts/meta-boxes/negative-review.php';
			echo ob_get_clean();
		}, self::NAME, 'normal', 'high' );
	}

	public function email_settings_meta_box() {
		add_meta_box( 'email-settings', __( 'E-Mail sent on negative response', 'review-bird' ), function () {
			ob_start();
			include Review_Bird()->get_plugin_dir_path() . 'templates/admin/posts/meta-boxes/admin-email.php';
			echo ob_get_clean();
		}, self::NAME, 'normal', 'high' );
	}

	public function enqueue_scripts() {
		// Get the current screen
		$screen = get_current_screen();
		if ( $screen->post_type === self::NAME ) {
			$rb = Review_Bird();
			// Scripts
			if ( ! wp_style_is( $rb->get_plugin_name() . '-single-' . self::NAME . '-js', 'registered' ) ) {
				$flow_script_asset = include( $rb->get_plugin_dir_path() . 'dist/js/admin/single-' . self::NAME . '.asset.php' );
				wp_register_script( $rb->get_plugin_name() . '-single-' . self::NAME . '-js', $rb->get_plugin_dir_url() . 'dist/js/admin/single-' . self::NAME . '.js', $flow_script_asset['dependencies'],
					$flow_script_asset['version'] );
			}
			wp_enqueue_script( $rb->get_plugin_name() . '-single-' . self::NAME . '-js' );
			wp_localize_script( $rb->get_plugin_name() . '-single-' . self::NAME . '-js', 'ReviewBird', array(
				'rest'      => array(
					'url'   => get_rest_url( null, 'review-bird/v1/' ),
					'nonce' => wp_create_nonce( 'wp_rest' ),
				),
				'flow_uuid' => get_post_meta( get_the_ID(), '_uuid', true ),
			) );
			// Styles
			if ( ! wp_style_is( $rb->get_plugin_name() . '-single-' . self::NAME . '-style', 'registered' ) ) {
				wp_register_style( $rb->get_plugin_name() . '-single-' . self::NAME . '-style', $rb->get_plugin_dir_url() . 'dist/css/admin/posts/flow.css', array(), $rb->get_version() );
			}
			wp_enqueue_style( $rb->get_plugin_name() . '-single-' . self::NAME . '-style' );
			// Media uploader
			wp_enqueue_media();
		}
	}
}