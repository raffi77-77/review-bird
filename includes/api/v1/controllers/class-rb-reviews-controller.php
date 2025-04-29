<?php

namespace Review_Bird\Includes\Api\V1\Controllers;

use Exception;
use Review_Bird\Includes\Data_Objects\Review;
use Review_Bird\Includes\Data_Objects\Flow;
use Review_Bird\Includes\Repositories\Review_Repository;
use Review_Bird\Includes\Repositories\Flow_Repository;
use Review_Bird\Includes\Services\Helper;
use Review_Bird\Includes\User_Manager;
use WP_REST_Server;

class Reviews_Controller extends Rest_Controller {
	protected string $generic_rest_base = 'chats';
	protected $rest_base = 'reviews/(?P<uuid>[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12})';

	public function register_routes() {
		register_rest_route( $this->namespace, '/' . $this->generic_rest_base, array(
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'create_item' ),
				'permission_callback' => array( $this, 'permission_callback' ),
				'args'                => $this->get_endpoint_args_for_item_schema(),
			),
			'schema' => $this->get_item_schema()
		) );
	}

	public function create_item( $request ) {
		try {
			$data    = $request->get_json_params();

			return rest_ensure_response( (new Review_Repository())->create( $data ?? [] ) );
		} catch ( Exception $e ) {
			Helper::log( $e, __METHOD__ );

			return Helper::get_wp_error( $e );
		}
	}

	protected function fill_personalized_params( $params ) {
		if ( ! current_user_can( 'manage_options' ) && ! is_admin() ) {
			$params['chatbot_user_uuid'] = User_Manager::instance()->get_current_user()->get_uuid();
		}

		return $params;
	}
	
}