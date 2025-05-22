<?php

namespace Review_Bird\Includes;

use Review_Bird\Includes\Api\Server;
use Review_Bird\Includes\Blocks\Flow;
use Review_Bird\Includes\Cpts\Custom_Post_Types;
use Review_Bird\Includes\Admin\Admin;
use Review_Bird\Includes\Frontend\Frontend;
use Review_Bird\Includes\Services\Install;

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
		$this->debug           = get_option( 'review_bird_debug', true ) ?? true;
		$this->plugin_name     = 'review-bird';
		$this->plugin_prefix   = 'review_bird';
		$this->plugin_dir_path = plugin_dir_path( dirname( __FILE__ ) );
		$this->plugin_dir_url  = plugin_dir_url( dirname( __FILE__ ) );
		$this->load_dependencies();
	}

	private function load_dependencies(): void {
		require_once $this->plugin_dir_path . 'vendor/autoload.php';
		require_once $this->plugin_dir_path . 'includes/traits/class-rb-singleton-trait.php';
		require_once $this->plugin_dir_path . 'includes/traits/class-rb-json-serializable-trait.php.php';
		
		require_once $this->plugin_dir_path . 'includes/interfaces/class-rb-database-strategy-interface.php';
		require_once $this->plugin_dir_path . 'includes/interfaces/class-rb-scheme-interface.php';
		
		require_once $this->plugin_dir_path . 'includes/repositories/class-rb-flow-repository.php';
		require_once $this->plugin_dir_path . 'includes/repositories/class-rb-review-repository.php';
		require_once $this->plugin_dir_path . 'includes/repositories/class-rb-setting-repository.php';
		
		require_once $this->plugin_dir_path . 'includes/services/class-rb-collection.php';
		require_once $this->plugin_dir_path . 'includes/services/class-rb-data-object-collection.php';
		require_once $this->plugin_dir_path . 'includes/services/class-rb-helper.php';
		require_once $this->plugin_dir_path . 'includes/services/class-rb-install.php';
		require_once $this->plugin_dir_path . 'includes/services/class-rb-freemius.php';
		require_once $this->plugin_dir_path . 'includes/services/class-rb-sanitizer.php';
		require_once $this->plugin_dir_path . 'includes/services/class-rb-validator.php';
		require_once $this->plugin_dir_path . 'includes/services/class-rb-review-service.php';

		require_once $this->plugin_dir_path . 'includes/api/class-rb-server.php';
		require_once $this->plugin_dir_path . 'includes/api/v1/controllers/class-rb-rest-controller.php';
		require_once $this->plugin_dir_path . 'includes/api/v1/controllers/class-rb-flows-controller.php';
		require_once $this->plugin_dir_path . 'includes/api/v1/controllers/class-rb-reviews-controller.php';
		require_once $this->plugin_dir_path . 'includes/api/v1/controllers/class-rb-settings-controller.php';

		require_once $this->plugin_dir_path . 'includes/database-strategies/class-rb-database-strategy.php';
		require_once $this->plugin_dir_path . 'includes/database-strategies/class-rb-wp-meta-query.php';
		require_once $this->plugin_dir_path . 'includes/database-strategies/class-rb-wp-options.php';
		require_once $this->plugin_dir_path . 'includes/database-strategies/class-rb-wp-query.php';
		require_once $this->plugin_dir_path . 'includes/database-strategies/class-rb-wpdb.php';
		
		require_once $this->plugin_dir_path . 'includes/cpts/class-rb-custom-post-types.php';
		require_once $this->plugin_dir_path . 'includes/cpts/flow/class-rb-custom-post-type.php';
		require_once $this->plugin_dir_path . 'includes/cpts/flow/class-rb-meta-scheme.php';
		
		require_once $this->plugin_dir_path . 'includes/admin/class-rb-admin.php';
		require_once $this->plugin_dir_path . 'includes/admin/class-rb-asset.php';
		require_once $this->plugin_dir_path . 'includes/admin/class-rb-ajax.php';
		require_once $this->plugin_dir_path . 'includes/admin/pages/setting/class-rb-page.php';
		require_once $this->plugin_dir_path . 'includes/admin/pages/review/class-rb-page.php';
		require_once $this->plugin_dir_path . 'includes/admin/pages/skin/class-rb-page.php';
		require_once $this->plugin_dir_path . 'includes/admin/pages/feedback/class-rb-page.php';
		
		require_once $this->plugin_dir_path . 'includes/exceptions/class-rb-exception.php';
		require_once $this->plugin_dir_path . 'includes/exceptions/class-rb-error-codes.php';

		require_once $this->plugin_dir_path . 'includes/blocks/class-rb-block.php';
		require_once $this->plugin_dir_path . 'includes/blocks/class-rb-flow.php';
		
		require_once $this->plugin_dir_path . 'includes/frontend/class-rb-frontend.php';
		
		require_once $this->plugin_dir_path . 'includes/data-objects/class-rb-data-object.php';
		require_once $this->plugin_dir_path . 'includes/data-objects/class-rb-wp-meta-data-object.php';
		require_once $this->plugin_dir_path . 'includes/data-objects/class-rb-wp-post-data-object.php';
		require_once $this->plugin_dir_path . 'includes/data-objects/class-rb-wpdb-data-object.php';
		require_once $this->plugin_dir_path . 'includes/data-objects/class-rb-flow.php';
		require_once $this->plugin_dir_path . 'includes/data-objects/class-rb-flow-meta.php';
		require_once $this->plugin_dir_path . 'includes/data-objects/class-rb-review.php';
		require_once $this->plugin_dir_path . 'includes/data-objects/class-rb-setting.php';
		
		require_once $this->plugin_dir_path . 'includes/utilities/class-rb-flow-utility.php';
	}

	private function set_locale(): void {
		add_action( 'plugins_loaded', function () {
			load_plugin_textdomain( 'review-bird', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		} );
	}

	public function register_cpts(): void {
		Custom_Post_Types::register();
	}

	public function register_blocks(): void {
		add_action( 'init', [ ( new Flow() ), 'register' ] );
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
		 Server::get_instance()->init();
	}

	public function get_debug(): bool {
		return $this->debug;
	}

	public function define_integrations() {
	}

	public function run(): void {
		$this->set_locale();
		$this->register_cpts();
		$this->register_blocks();
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
