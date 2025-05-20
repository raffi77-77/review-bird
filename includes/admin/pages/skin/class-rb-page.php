<?php

namespace Review_Bird\Includes\Admin\Pages\Skin;

use Review_Bird\Includes\Review_Bird;

class Page {

	static $menu_slug = 'review-bird-skins';
	static string $option_group = 'skins';
	protected string $option_prefix;
	protected ?array $settings;
	public string $prefix;
	public function __construct() {
		$this->prefix        = '_utilities_flow_';
		$this->option_prefix = Review_Bird::get_instance()->get_plugin_prefix() . $this->prefix;
		add_action( 'admin_init', [ $this, 'register_settings' ] );
	}

	public function add_submenu_page() {
		$skins = add_submenu_page( 'review-bird', __( 'Skins', 'review-bird' ), __( 'Skins', 'review-bird' ), 'manage_options', self::$menu_slug, array( $this, 'render' ), 3 );
		add_action( 'admin_print_styles-' . $skins, array( $this, 'register_settings_styles' ) );
		add_action( 'admin_print_scripts-' . $skins, array( $this, 'register_settings_scripts' ) );
	}

	public function register_settings() {
		$option_prefix = $this->option_prefix;
		add_settings_section( 'skins', __( '', 'review-bird' ), null, self::$menu_slug );
		register_setting( self::$option_group, $option_prefix . 'skin', [ 'type' => 'string', 'sanitize_callback' => 'sanitize_key', ] );
		add_settings_field( $option_prefix . 'skin', __( 'Choose your Skin:', 'review-bird' ), [ $this, 'render_skin' ], self::$menu_slug, 'skins' );
	}

	public function render() {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Skins', 'review-bird' ); ?></h1>
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
	
	public function render_skin() {
		include Review_Bird()->get_plugin_dir_path() . 'templates/admin/pages/skin.php';
	}

	public function register_settings_styles(): void {
		$rb = Review_Bird();
		if ( ! wp_style_is( $rb->get_plugin_name() . '-page-skin-style', 'registered' ) ) {
			wp_register_style( $rb->get_plugin_name() . '-page-skin-style', $rb->get_plugin_dir_url() . 'dist/css/admin/pages/skin.css', array(), $rb->get_version() );
		}
		wp_enqueue_style( $rb->get_plugin_name() . '-page-skin-style' );
	}
    
    public function register_settings_scripts(): void {
        $rb = Review_Bird();
        if ( ! wp_script_is( $rb->get_plugin_name() . '-page-skin-script', 'registered' ) ) {
            wp_register_script($rb->get_plugin_name() . '-page-skin-script', $rb->get_plugin_dir_url() . 'dist/js/admin/page-review-bird-skins.js', array(), $rb->get_version());
        }
        wp_enqueue_script( $rb->get_plugin_name() . '-page-skin-script' );
    }
}

