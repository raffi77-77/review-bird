<?php

namespace Review_Bird\Includes\Api\V1\Controllers;

use Exception;
use Review_Bird\Includes\Data_Objects\Flow;
use Review_Bird\Includes\Repositories\Review_Repository;
use Review_Bird\Includes\Services\Helper;
use WP_REST_Server;

class Reviews_Controller extends Rest_Controller {
	protected string $generic_rest_base = 'reviews';

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
			$data            = $request->get_json_params();
			$flow            = Flow::find_by_uuid( $data['flow_uuid'] );
			$data['flow_id'] = $flow->id;

			return rest_ensure_response( ( new Review_Repository() )->create( $data ) );
		} catch ( Exception $e ) {
			Helper::log( $e, __METHOD__ );

			return Helper::get_wp_error( $e );
		}
	}

	public function get_item_schema() {
		if ( $this->schema ) {
			// Since WordPress 5.3, the schema can be cached in the $schema property.
			return $this->schema;
		}
		$this->schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'vector',
			'type'       => 'object',
			'properties' => array(
				'id'        => array(
					'description' => __( 'Unique identifier for the resource.', 'review-bird' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true
				),
				'message'   => array(
					'description' => __( 'Review message', 'review-bird' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'arg_options' => array(// 'sanitize_callback' => 'wp_filter_post_kses',
					),
				),
				'username'  => array(
					'description' => __( 'Review message', 'review-bird' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'arg_options' => array(// 'sanitize_callback' => 'wp_filter_post_kses',
					),
				),
				'uuid'      => array(
					'description' => __( 'Universal unique identifier for the review', 'review-bird' ),
					'type'        => 'string',
					'format'      => 'uuid',
					'context'     => array( 'view' ),
				),
				'flow_uuid' => array(
					'description' => __( 'Universal unique identifier for the flow', 'review-bird' ),
					'type'        => 'string',
					'format'      => 'uuid',
					'required'    => true,
					'context'     => array( 'view', 'create' ),
					'arg_options' => array(
						'validate_callback' => function ( $value, $request, $param ) {
							return Flow::exists_by_uuid( $value );
						},
					)
				),
				'rating'    => array(
					'description' => __( 'Review rating', 'review-bird' ),
					'type'        => 'integer',
					'arg_options' => array(
						'validate_callback' => function ( $value, $request, $param ) {
							return is_numeric( $value ) && (int) $value >= 0 && (int) $value <= 5;
						},
					)
				),
				'like'      => array(
					'description' => __( 'Review like', 'review-bird' ),
					'type'        => 'integer',
					'enum'        => [ 0, 1 ]
				)
			),
		);

		return $this->schema;
	}


}