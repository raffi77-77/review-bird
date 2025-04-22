<?php

namespace Review_Bird\Includes\Data_Objects;

use Review_Bird\Includes\Database_Strategies\WP_Options;
use Review_Bird\Includes\Services\Helper;

class Setting extends Data_Object {

	const SETTING_PREFIX = 'limb.';
	const KEY_REGEXP = 'a-zA-Z0-9._-';
	const TABLE_NAME = 'options';
	public string $key;
	public $value;

	public function __construct( $instance = null ) {
		if ( is_array( $instance ) && ! empty( $instance['value'] ) && $this->isJson( $instance['value'] ) ) {
			$instance['value'] = Helper::maybe_json_decode( $instance['value'] );
		}
		parent::__construct( $instance );
	}

	static function get_db_strategy() {
		return WP_Options::instance();
	}

	public function get_value() {
		return $this->value;
	}

	public function set_value( $value ) {
		$this->value = $value;
	}

	public function get_key() {
		return $this->key;
	}

	public function set_key( $key ) {
		$this->key = $key;
	}

	public static function sanitize_key( $key ) {
		// Allow only alphanumeric characters and . _ -
		return preg_replace( '/[^' . self::KEY_REGEXP . ']/', '', sanitize_text_field( $key ) );
	}

	public static function find( $id ) {
		if ( ! str_starts_with( $id, self::SETTING_PREFIX ) ) {
			$id = self::SETTING_PREFIX . $id;
		}

		return parent::find( $id );
	}
}