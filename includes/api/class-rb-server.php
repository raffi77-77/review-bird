<?php

namespace Review_Bird\Includes\Api;

use Review_Bird\Includes\Api\V1\Controllers\Rest_Controller;

class Server {

	private static ?self $_instance = null;

	/**
	 * @var Rest_Controller[]
	 */
	protected array $controllers = [];

	public static function get_instance(): self {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function init() {
		add_action( 'rest_api_init', array( $this, 'register_rest_routes' ), 10 );
	}

	public function register_rest_routes() {
		foreach ( $this->get_controllers() as $key => $controller ) {
			$this->controllers[ $key ] = new $controller;
			$this->controllers[ $key ]->register_routes();
		}
	}

	public function get_controllers(): array {
		return [
			'flows' => 'Review_Bird\\Includes\\Api\\V1\\Controllers\\Flows_Controller',
			'reviews'    => 'Review_Bird\\Includes\\Api\\V1\\Controllers\\Reviews_Controller'
		];
	}
}