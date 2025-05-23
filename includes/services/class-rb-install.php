<?php

namespace Review_Bird\Includes\Services;

use Review_Bird\Includes\Admin\Pages\Setting\Page;
use Review_Bird\Includes\Cpts\Flow\Meta_Scheme;
use Review_Bird\Includes\Data_Objects\Setting;
use Review_Bird\Includes\Traits\SingletonTrait;

class Install {

	use SingletonTrait;

	protected $wpdb;

	protected function __construct() {
		global $wpdb;
		$this->wpdb = $wpdb;
	}

	public static function activate(): void {
		$instance = self::instance();
		$instance->create_tables();
		$instance->seed();
	}

	public static function deactivate() {
		// self::instance()->drop_tables();
	}

	public function seed() {
		try {
			$this->create_default_settings();
		} catch ( \Exception $e ) {
			Helper::log( [], $e->getMessage() );
		}
	}

	public function create_default_settings() {
		$meta_scheme_rules = Meta_Scheme::rules();
		$flow_prefix       = Review_Bird()->get_plugin_prefix() . Page::$prefix;
		$this->maybe_add_setting( "{$flow_prefix}question", $meta_scheme_rules['question']['default'] ?? '' );
		$this->maybe_add_setting( "{$flow_prefix}review_box_text", $meta_scheme_rules['review_box_text']['default'] ?? '' );
		$this->maybe_add_setting( "{$flow_prefix}username_placeholder", $meta_scheme_rules['username_placeholder']['default'] ?? '' );
		$this->maybe_add_setting( "{$flow_prefix}review_placeholder", $meta_scheme_rules['review_placeholder']['default'] ?? '' );
		$this->maybe_add_setting( "{$flow_prefix}success_message", $meta_scheme_rules['success_message']['default'] ?? '' );
		$this->maybe_add_setting( "{$flow_prefix}emails_on_negative_review", $meta_scheme_rules['emails_on_negative_review']['default'] ?? '' );
		$this->maybe_add_setting( "{$flow_prefix}skin", $meta_scheme_rules['skin']['default'] ?? '' );
	}

	public function create_tables() {
		$wpdb = $this->wpdb;
		// Include necessary WordPress upgrade functions and execute the query.
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( self::get_schemas( $wpdb ) );
	}

	protected static function get_schemas( $wpdb ) {
		$prefix  = Review_Bird()->get_plugin_prefix();
		$collate = '';
		$tables  = "
CREATE TABLE {$wpdb->prefix}{$prefix}_reviews (
  id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  uuid char(36) NOT NULL,
  flow_id bigint(20) unsigned NOT NULL,
  flow_uuid char(36) NOT NULL,
  message text NULL,
  user_id bigint(20) unsigned NULL, 
  username varchar(64) NULL, 
  rating smallint NULL,
  `like` tinyint(1) NULL,
  target varchar(1024) NULL,
  created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
  updated_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY uuid (uuid),
  KEY flow_id (flow_id),
  KEY `user_id` (`user_id`),
  KEY `like` (`like`),
  KEY `rating` (`rating`),
  KEY `created_at` (`created_at`)
) $collate;";

		return $tables;
	}

	public static function get_tables() {
		$wpdb   = self::instance()->wpdb;
		$prefix = Review_Bird()->get_plugin_prefix();
		$tables = array(
			"{$wpdb->prefix}_{$prefix}_reviews",
		);

		return apply_filters( 'sr_rb_install_get_tables', $tables );
	}

	public function drop_tables() {
		$wpdb   = $this->wpdb;
		$tables = $this::get_tables();
		foreach ( $tables as $table ) {
			// phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$wpdb->query( "DROP TABLE IF EXISTS {$table}" );
			// phpcs:enable WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		}
	}

	private function maybe_add_setting( $key, $value ) {
		if ( ! Setting::exists( $key ) ) {
			Setting::create( [ 'key' => $key, 'value' => $value ] );
		}
	}

}
