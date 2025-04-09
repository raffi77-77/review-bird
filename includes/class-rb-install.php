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
		$collate = '';
		// TODO setup engine as InnoDB to support transactions. Mainly to lock the chats table
		$tables  = "
CREATE TABLE {$wpdb->prefix}something (
) $collate;";
// TODO add chat_participants table
		return $tables;
	}

	public static function get_tables() {
		$wpdb   = self::instance()->wpdb;
		$tables = array(
			"{$wpdb->prefix}something",
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
