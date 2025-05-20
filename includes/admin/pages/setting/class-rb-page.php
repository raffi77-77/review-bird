<?php

namespace Review_Bird\Includes\Admin\Pages\Setting;

use Review_Bird\Includes\Data_Objects\Setting;
use Review_Bird\Includes\Review_Bird;

class Page {

	static string $menu_slug = 'review-bird';
	static string $option_group = 'main';
	protected string $option_prefix;
	protected ?array $settings;
	public string $prefix;

	public function __construct() {
		$this->prefix        = '_utilities_flow_';
		$this->option_prefix = Review_Bird::get_instance()->get_plugin_prefix() . $this->prefix;
		add_action( 'admin_init', [ $this, 'register_settings' ] );
	}

	public function add_submenu_page() {
		$settings = add_submenu_page( 'review-bird', __( 'Settings', 'review-bird' ), __( 'Settings', 'review-bird' ), 'manage_options', self::$menu_slug, array( $this, 'render' ), 1 );
		// add_action( 'admin_print_scripts-' . $settings, array( $this, 'register_settings_scripts' ) );
		add_action( 'admin_print_styles-' . $settings, array( $this, 'register_settings_styles' ) );
	}

	public function register_settings() {
		$option_prefix = $this->option_prefix;
		// Sections        
		add_settings_section( 'flow_slug', '', null, self::$menu_slug );
		add_settings_section( 'new_flow', __( 'Defaults for Flows', 'review-bird' ), null, self::$menu_slug );
		register_setting( self::$option_group, $option_prefix . 'flow_slug', [ 'type' => 'string', 'sanitize_callback' => 'sanitize_key', ] );
		add_settings_field( $option_prefix . 'flow_slug', __( 'Flow Slug:', 'review-bird' ), [ $this, 'render_flow_slug_field' ], self::$menu_slug, 'flow_slug' );
		register_setting( self::$option_group, $option_prefix . 'question', [ 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field', ] );
		add_settings_field( $option_prefix . 'question', __( 'Question:', 'review-bird' ), [ $this, 'render_question_field' ], self::$menu_slug, 'new_flow' );
		add_settings_field( 'negative_review_response', 'Negative Review', function () {
		}, self::$menu_slug, 'new_flow' );
		register_setting( self::$option_group, $option_prefix . 'review_box_text', [ 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field', ] );
		add_settings_field( $option_prefix . 'review_box_text', __( 'Box Text:', 'review-bird' ), [ $this, 'render_negative_review_box_field' ], self::$menu_slug, 'new_flow' );
		register_setting( self::$option_group, $option_prefix . 'success_message', [ 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field', ] );
		add_settings_field( $option_prefix . 'success_message', __( 'Success Message:', 'review-bird' ), [ $this, 'render_review_entry_success_message' ], self::$menu_slug, 'new_flow' );
		add_settings_field( 'negative_review_responses', 'Form Placeholders', function () {
		}, self::$menu_slug, 'new_flow' );
		register_setting( self::$option_group, $option_prefix . 'username_placeholder', [ 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field', ] );
		add_settings_field( $option_prefix . 'username_placeholder', __( 'Username field:', 'review-bird' ), [ $this, 'render_review_username_placeholder' ], self::$menu_slug, 'new_flow' );
		register_setting( self::$option_group, $option_prefix . 'review_placeholder', [ 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field', ] );
		add_settings_field( $option_prefix . 'review_placeholder', __( 'Review field:', 'review-bird' ), [ $this, 'render_review_text_placeholder' ], self::$menu_slug, 'new_flow' );
		register_setting( self::$option_group, $option_prefix . 'emails_on_negative_review', [ 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field', ] );
		add_settings_field( $option_prefix . 'emails_on_negative_review', __( 'Notify To E-Mails', 'review-bird' ), [ $this, 'render_notify_to_emails' ], self::$menu_slug, 'new_flow' );
	}

	public function render() {
		?>
        <div class="wrap">
            <h1><?php esc_html_e( 'Settings', 'review-bird' ); ?></h1>
            <form method="post" action="options.php" enctype="multipart/form-data">
				<?php
				settings_fields( self::$option_group );
				do_settings_sections( self::$menu_slug );
				submit_button();
				?>
            </form>
        </div>
		<?php
	}

	public function render_flow_slug_field() {
		?>
        <input type="text" placeholder="Review" name="<?= $this->option_prefix ?>flow_slug" value="<?php echo esc_attr( Setting::find( $this->prefix . 'flow_slug' )->get_value() ?? 'review' ); ?>" class="regular-text 
        rw-settings-input">
        <p><?= sprintf(__( 'After updating the slug, always update the permalinks %s here %s', 'review-bird' ), '<a href="options-permalink.php">', '</a>') ?></p>
		<?php
	}

	public function render_question_field() {
		$value = Setting::find( $this->prefix . 'question' )->get_value() ?? '';
		?>
        <textarea name="<?= $this->option_prefix ?>question" value="<?php echo esc_attr( $value ); ?>"
                  class="regular-text rw-settings-textarea"><?= esc_html( $value ) ?></textarea>
        <p><?= __( 'The shortcode {site-name} displays the site name as defined in WordPress under Settings → General.', 'review-bird' ) ?></p>
		<?php
	}

	public function render_negative_review_box_field() {
		$value = Setting::find( $this->prefix . 'review_box_text' )->get_value() ?? '';
		?>
        <textarea name="<?= $this->option_prefix ?>review_box_text" value="<?php echo esc_attr( $value ); ?>"
                  class="regular-text rw-settings-textarea"><?= esc_html( $value ) ?></textarea>
		<?php
	}

	public function render_review_entry_success_message() {
		$value = Setting::find( $this->prefix . 'success_message' )->get_value() ?? '';
		?>
        <textarea name="<?= $this->option_prefix ?>success_message" value="<?php echo esc_attr( $value ); ?>"
                  class="regular-text rw-settings-textarea"><?= esc_html( $value ) ?></textarea>
		<?php
	}

	public function render_review_username_placeholder() {
		?>
        <input type="text" name="<?= $this->option_prefix ?>username_placeholder" value="<?php echo esc_attr( Setting::find( $this->prefix . 'username_placeholder' )->get_value() ?? '' ); ?>"
               class="regular-text rw-settings-input">
        <p><?= __( 'This is a text for the name', 'review-bird' ) ?></p>
		<?php
	}

	public function render_review_text_placeholder() {
		?>
        <input type="text" name="<?= $this->option_prefix ?>review_placeholder" value="<?php echo esc_attr( Setting::find( $this->prefix . 'review_placeholder' )->get_value() ?? '' ); ?>"
               class="regular-text rw-settings-input">
        <p><?= __( 'This is a text for the review field', 'review-bird' ) ?></p>
		<?php
	}

	public function render_notify_to_emails() {
		?>
        <input type="text" name="<?= $this->option_prefix ?>emails_on_negative_review" value="<?php echo esc_attr( Setting::find( $this->prefix . 'emails_on_negative_review' )->get_value() ?? '' ); ?>"
               class="regular-text rw-settings-input">
        <p><?= __( 'Email address(es) to receive negative feedback. You can enter multiple addresses, separated by commas.', 'review-bird' ) ?></p>
		<?php
	}

	public function sanitize_settings( $options ) {
		if ( ! is_array( $options ) ) {
			$options = [];
		}
		$sanitized = [];
		foreach ( $options as $key => $option ) {
			if ( isset( $option ) ) {
				$sanitized[ $key ] = sanitize_text_field( $option );
			}
		}

		return $sanitized;
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
			wp_register_style( $rb->get_plugin_name() . '-page-settings-style', $rb->get_plugin_dir_url() . 'dist/css/admin/pages/settings.css', array(), $rb->get_version() );
		}
		wp_enqueue_style( $rb->get_plugin_name() . '-page-settings-style' );
		if ( file_exists( $rb->get_plugin_dir_path() . 'dist/js/admin/something-to-change-page-settings.css' ) ) {
			if ( ! wp_style_is( $rb->get_plugin_name() . '-page-settings-js-style', 'registered' ) ) {
				wp_register_style( $rb->get_plugin_name() . '-page-settings-js-style', $rb->get_plugin_dir_url() . 'dist/js/admin/something-to-change-page-settings.css', array(), $rb->get_version() );
			}
			wp_enqueue_style( $rb->get_plugin_name() . '-page-settings-js-style' );
		}
	}

}

