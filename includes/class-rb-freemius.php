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
			return fs_dynamic_init( array(
				'id'                  => '18636',
				'slug'                => 'review-bird',
				'premium_slug'        => 'review-bird-pro',
				'type'                => 'plugin',
				'public_key'          => 'pk_447a04094d7ba96f7470d4f9a7329',
				'is_premium'          => true,
				'premium_suffix'      => '(PRO)',
				'has_addons'          => false,
				'has_paid_plans'      => false,
				'menu'                => array(
					'slug'           => 'review-bird',
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
