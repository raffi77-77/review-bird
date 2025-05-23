<?php

namespace Review_Bird\Includes\Traits;


trait SingletonTrait {
	
	protected static $instance = null;

	private function __construct() {}

	final public static function instance(): self {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}
		return static::$instance;
	}

	private function __clone() {}

	final public function __wakeup() {
		wc_doing_it_wrong( __FUNCTION__, __( 'Unserializing instances of this class is forbidden.', 'review-bird' ), '4.6' );
		die();
	}
}
