<?php

namespace Review_Bird\Includes;

use Review_Bird\Includes\Custom_Post_Types;
use Review_Bird\Includes\Frontend\Frontend;
use Review_Bird\Includes\Admin\Admin;

final class Review_Bird {

	private static ?self $_instance = null;
	private bool $debug;
	protected array $integrations = [];
	private string $plugin_name;
	private string $plugin_dir_path;
	private string $plugin_dir_url;
	private string $plugin_prefix;
	private string $version;

	private function __construct() {
		$this->version         = defined( 'SR_RB_VERSION' ) ? SR_RB_VERSION : '1.0.0';
		$this->debug           = get_option( 'review_bird_debug', true ) ?? false;
		$this->plugin_name     = 'review-bird';
		$this->plugin_prefix   = 'sr_rb';
		$this->plugin_dir_path = plugin_dir_path( dirname( __FILE__ ) );
		$this->plugin_dir_url  = plugin_dir_url( dirname( __FILE__ ) );
		$this->load_dependencies();
	}

	private function load_dependencies(): void {
		require_once $this->plugin_dir_path . 'vendor/autoload.php';
		require_once $this->plugin_dir_path . 'includes/traits/class-rb-singleton-trait.php';
		require_once $this->plugin_dir_path . 'includes/class-rb-install.php';
		require_once $this->plugin_dir_path . 'includes/class-rb-freemius.php';
		require_once $this->plugin_dir_path . 'includes/class-rb-custom-post-types.php';
		require_once $this->plugin_dir_path . 'includes/admin/class-rb-admin.php';
		require_once $this->plugin_dir_path . 'includes/admin/class-rb-ajax.php';
		require_once $this->plugin_dir_path . 'includes/exceptions/class-rb-exception.php';
		require_once $this->plugin_dir_path . 'includes/frontend/class-rb-frontend.php';
		require_once $this->plugin_dir_path . 'includes/services/class-rb-helper.php';
	}

	private function set_locale(): void {
		add_action( 'plugins_loaded', function () {
			load_plugin_textdomain( 'review-bird', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		} );
	}

	public function register_cpts(): void {
		Custom_Post_Types::setup();
	}

	private function define_admin_classes(): void {
		Admin::instance()->boot();
	}

	private function frontend(): void {
		Frontend::instance()->init();
	}
	
	private function init_activation_hooks(): void {
		register_activation_hook( SR_RB_FILE, array( Install::class, 'activate' ) );
		register_deactivation_hook( SR_RB_FILE, array( Install::class, 'deactivate' ) );
	}

	public static function get_instance(): self {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function get_plugin_prefix(): string {
		return $this->plugin_prefix;
	}

	private function api_init(): void {
		// Server::get_instance()->init();
	}

	public function get_debug(): bool {
		return $this->debug;
	}

	public function define_integrations() {
	}

	public function run(): void {
		$this->set_locale();
		$this->register_cpts();
		$this->init_activation_hooks();
		$this->define_admin_classes();
		$this->frontend();
		$this->api_init();
	}

	public function get_plugin_name(): string {
		return $this->plugin_name;
	}

	public function get_version(): string {
		return $this->version;
	}

	public function get_plugin_dir_path(): string {
		return $this->plugin_dir_path;
	}

	public function get_plugin_dir_url(): string {
		return $this->plugin_dir_url;
	}

}
