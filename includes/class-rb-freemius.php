<?php

namespace Review_Bird\Includes;

use Review_Bird\Includes\Services\Helper;
use Exception;
use Review_Bird\Includes\Traits\SingletonTrait;

class Freemius {
	
	use SingletonTrait;

	const PREMIUM_OPTION_KEY = 'review_bird_premium';
	
	public ?\Freemius $fs;

	public string $plugin_path;

	public function __construct() {
		$this->fs             = $this->fs_init();
		if ( ! empty( $fs ) ) {
			$this->fs->add_filter( 'plugin_icon', [ $this, 'plugin_icon' ] );
		}
	}

	private function fs_init(): ?\Freemius {
		try {
			// Include Freemius SDK.
			require_once plugin_dir_path( __DIR__ ) . '/vendor/freemius/start.php';
			return fs_dynamic_init( array(
				'id'                  => '6959',
				'slug'                => 'review-bird',
				'premium_slug'        => 'review-bird-pro',
				'type'                => 'plugin',
				'public_key'          => 'pk_e348807215df985c848c86b883ee3',
				'is_premium'          => true,
				'premium_suffix'      => '(PRO)',
				// If your plugin is a serviceware, set this option to false.
				'has_premium_version' => true,
				'has_addons'          => false,
				'has_paid_plans'      => true,
				'has_affiliation'     => 'selected',
				'menu'                => array(
					'slug'           => 'review-bird',
					'support'        => false,
					'affiliation'    => false,
				),
			) );
		} catch ( Exception $e ) {
			Helper::log( $e, __METHOD__ );

			return null;
		}
	}

	function plugin_icon() {
		return dirname( __FILE__ ) . '/assets/img/dms-logo.jpg';
	}

	public function get_fs(): ?\Freemius {
		return $this->fs;
	}
}
