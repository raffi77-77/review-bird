<?php

namespace Review_Bird\Includes\Api\V1\Controllers;

use Review_Bird\Includes\Data_Objects\Setting;
use Review_Bird\Includes\Services\Helper;
use Review_Bird\Includes\Repositories\Setting_Repository;

class Settings_Controller extends Rest_Controller {

	protected $rest_base = 'settings';

	public function register_routes() {
		register_rest_route( $this->namespace, '/' . $this->rest_base . '/', array(
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_items' ),
				'permission_callback' => array( $this, 'permission_callback' ),
			),
		) );
		register_rest_route( $this->namespace, '/' . $this->rest_base . '/', array(
			array(
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => array( $this, 'create_item' ),
				'permission_callback' => array( $this, 'permission_callback' ),
				'args'                => $this->get_endpoint_args_for_item_schema()
			),
			'schema' => array( $this, 'get_item_schema' ),
		) );
		register_rest_route( $this->namespace, '/' . $this->rest_base . '/(?P<key>[' . Setting::KEY_REGEXP . ']+)',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_item' ),
					'permission_callback' => array( $this, 'permission_callback' ),
				),
				array(
					'methods'             => \WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'delete_item' ),
					'permission_callback' => array( $this, 'permission_callback' ),
				),
				array(
					'methods'             => \WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_item' ),
					'permission_callback' => array( $this, 'permission_callback' ),
					'args'                => $this->get_endpoint_args_for_item_schema()
				),
				'schema' => array( $this, 'get_item_schema' ),
			) );
	}

	public function permission_callback( $request ): bool {
		if ( ! parent::permission_callback( $request ) ) {
			return false;
		}

		return current_user_can( 'manage_options' );
	}

	public function create_item( $request ) {
		try {
			return rest_ensure_response( ( new Setting_Repository() )->create( $request->get_json_params() ) );
		} catch ( \Exception $e ) {
			Helper::log( $e, __METHOD__ );

			return Helper::get_wp_error( $e );
		}
	}

	public function update_item( $request ) {
		try {
			return rest_ensure_response( ( new Setting_Repository() )->update( $request->get_param( 'key' ),
				$request->get_json_params() ) );
		} catch ( \Exception $e ) {
			Helper::log( $e, __METHOD__ );

			return Helper::get_wp_error( $e );
		}
	}

	public function get_items( $request ) {
		try {
			return rest_ensure_response( ( new Setting_Repository() )->get_items( $request->get_query_params() ) );
		} catch ( \Exception $e ) {
			Helper::log( $e, __METHOD__ );

			return Helper::get_wp_error( $e );
		}
	}

	public function get_item( $request ) {
		try {
			return rest_ensure_response( Setting::find( $request->get_param( 'key' ) ) );
		} catch ( \Exception $e ) {
			Helper::log( $e, __METHOD__ );

			return Helper::get_wp_error( $e );
		}
	}

	public function delete_item( $request ) {
		try {
			return rest_ensure_response( ( new Setting_Repository() )->delete( $request->get_url_params() ) );
		} catch ( \Exception $e ) {
			Helper::log( $e, __METHOD__ );

			return Helper::get_wp_error( $e );
		}
	}

	public function get_item_schema(): array {
		if ( $this->schema ) {
			// Return cached schema if already set.
			return $this->schema;
		}

		$this->schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'custom_item',
			'type'       => 'object',
			'properties' => array(
				'key'   => array(
					'description' => esc_html__( 'Unique identifier for the object.', 'review-bird' ),
					'type'        => 'string',
					'required'    => true,
					'arg_options' => array(
						'required'          => true,
						'sanitize_callback' => 'sanitize_text_field',
						'validate_callback' => function ( $value ) {
							return ! empty( $value );
						}
					)
				),
				'value' => array(
					'description' => esc_html__( 'Item value.', 'review-bird' ),
					'required'    => true,
					'type'        => array( 'string', 'integer', 'array', 'object', 'null' ),
					'arg_options' => array(
						'required'          => true,
						'validate_callback' => function ( $value ) {
							return ! empty( $value );
						}
					)
				)
			)
		);

		return $this->schema;
	}
}