<?php

namespace Review_Bird\Includes\Api\V1\Controllers;

use Exception;
use Review_Bird\Includes\Data_Objects\Flow;
use Review_Bird\Includes\Repositories\Flow_Repository;
use Review_Bird\Includes\Services\Helper;
use WP_REST_Server;

class Flows_Controller extends Rest_Controller {

	protected $rest_base = 'flows';

	public function register_routes() {
		register_rest_route( $this->namespace, '/' . $this->rest_base . '/(?P<uuid>[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12})', array(
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_item' ),
				'permission_callback' => array( $this, 'get_item_permissions_check' ),
				'args'                => array(
					'uuid'    => array(
						'type'              => 'string',
						'validate_callback' => function ( $value ) {
							return ! empty( Flow::exists_by_uuid( $value ) );
						}
					),
					'include' => array(
						'description' => __( 'Include extra data with chatbot.', 'limb-ai' ),
						'type'        => 'array'
					)
				)
			),
		) );
	}

	public function get_item( $request ) {
		try {
			return rest_ensure_response( $this->prepare_item( ( new Flow_Repository() )->get_item( $request->get_param( 'uuid' ) ), $request ) );
		} catch ( Exception $e ) {
			Helper::log( $e, __METHOD__ );

			return Helper::get_wp_error( $e );
		}
	}

	public function prepare_item( $item, $request ) {
		if ( is_a( $item, \WP_Error::class ) ) {
			return $item;
		}
		if ( ! is_a( $item, Flow::class ) ) {
			throw new Exception( __( 'Unknown item type.', 'limb-ai' ) );
		}
		$include = $request->get_param( 'include' );
		if ( ! empty( $include ) && is_array( $include ) ) {
			foreach ( $include as $relation ) {
				if ( method_exists( $item, $relation ) ) {
					$item->included[ $relation ] = $item->{$relation}();
				}
			}
		}

		// Extra manipulations can be made here
		return $item;
	}

	public function get_item_permissions_check( $request ) {
		if ( $this->permission_callback( $request ) ) {
			return true;
		}

		return false;
	}
}