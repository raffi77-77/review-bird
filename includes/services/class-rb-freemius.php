<?php

namespace Review_Bird\Includes\Services;

use Exception;
use Review_Bird\Includes\Review_Bird;
use Review_Bird\Includes\Traits\SingletonTrait;

class Freemius {

	use SingletonTrait;

	const PREMIUM_OPTION_KEY = 'review_bird_premium';

	public ?\Freemius $fs;

	public string $plugin_path;

	public function __construct() {
		$this->fs = $this->fs_init();
		if ( ! empty( $fs ) ) {
			$this->fs->add_filter( 'plugin_icon', [ $this, 'plugin_icon' ] );
		}
	}

	private function fs_init(): ?\Freemius {
		try {
			return fs_dynamic_init( array(
				'id'             => '19172',
				'slug'           => 'review-bird',
				'premium_slug'   => 'review-bird-pro',
				'type'           => 'plugin',
				'public_key'     => 'pk_7c426b1d7d57bb7e3852aa1d431eb',
				'is_premium'     => true,
				'premium_suffix' => '(PRO)',
				'has_addons'     => false,
				'has_paid_plans' => true,
				'menu'           => array(
					'slug' => 'review-bird',
					'icon' => Review_Bird::get_instance()->get_plugin_dir_url() . 'resources/admin/icon.png',
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
