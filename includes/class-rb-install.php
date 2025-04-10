<?php

namespace Review_Bird\Includes;

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
	}
	
	public function create_default_settings() {
	}
	
	public function create_tables() {
		$wpdb = $this->wpdb;
		// Include necessary WordPress upgrade functions and execute the query.
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( self::get_schemas( $wpdb ) );
	}

	protected static function get_schemas( $wpdb ) {
		$prefix = Review_Bird()->get_plugin_prefix();
		$collate = '';
		$tables  = "
CREATE TABLE {$wpdb->prefix}{$prefix}_reviews (
  id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  uuid char(36) NOT NULL,
  flow_id bigint(20) unsigned NOT NULL,
  message text NULL,
  user_id bigint(20) unsigned NULL, 
  rating smallint NULL,
  created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
  updated_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY uuid (uuid),
  KEY flow_id (flow_id)
) $collate;
CREATE TABLE {$wpdb->prefix}{$prefix}_questions (
  id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  uuid char(36) NOT NULL,
  title text NOT NULL,
  flow_id bigint(20) unsigned NOT NULL,
  `type` tinyint(1) NULL,
  `required` tinyint(1) NULL,
  `order` smallint(1) NULL,
  created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
  updated_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY uuid (uuid),
  KEY flow_id (flow_id),
  KEY `type` (`type`),
  KEY `required` (`required`),
  KEY `order` (`order`)
) $collate;
CREATE TABLE {$wpdb->prefix}{$prefix}_answers (
  id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  uuid char(36) NOT NULL,
  `value` text NULL,
  review_id bigint(20) unsigned NOT NULL,
  question_id bigint(20) unsigned NOT NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY uuid (uuid),
  KEY review_id (review_id),
  KEY question_id (question_id)
) $collate;";
		return $tables;
	}

	public static function get_tables() {
		$wpdb   = self::instance()->wpdb;
		$prefix = Review_Bird()->get_plugin_prefix();
		$tables = array(
			"{$wpdb->prefix}_{$prefix}_reviews",
			"{$wpdb->prefix}_{$prefix}_questions",
			"{$wpdb->prefix}_{$prefix}_answers",
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

}
