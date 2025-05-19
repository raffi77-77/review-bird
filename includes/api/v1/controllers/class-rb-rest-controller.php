<?php

namespace Review_Bird\Includes\Api\V1\Controllers;

use Review_Bird\Includes\Services\Collection;
use Review_Bird\Includes\User_Manager;
use WP_REST_Request;

abstract class Rest_Controller extends \WP_REST_Controller {

	protected $namespace = 'review-bird/v1';

	public function permission_callback( $request ) {
		if ( is_user_logged_in() ) {
			// So no matter from where the request came from, the user is authorized
			return true;
		} else {
			// If not logged in, then nonce should be verified
			if ( $this->nonce_is_verified( $request ) ) {
				return true;
			}
		}

		return false;
	}

	public function nonce_is_verified( $request ) {
		return wp_verify_nonce( $request->get_header( 'X-WP-Nonce' ), 'wp_rest' );
	}

	public function get_collection_params() {
		return parent::get_collection_params() + array(
				'orderby' => array(
					'description'       => __( 'Sort the collection by attribute.', 'review-bird' ),
					'type'              => 'string',
					'default'           => 'created_at',
					'enum'              => array( 'created_at', 'id', 'updated_at' ),
					'validate_callback' => 'rest_validate_request_arg',
				),
				'order'   => array(
					'description'       => __( 'Order sort attribute ascending or descending.', 'review-bird' ),
					'type'              => 'string',
					'default'           => 'desc',
					'enum'              => array( 'asc', 'desc' ),
					'validate_callback' => 'rest_validate_request_arg',
				),
			);
	}

	public function prepare_collection( Collection $collection, $request ) {
		if ( $collection->is_empty() ) {
			return $collection;
		}
		if ( $include = $request->get_param( 'include' ) ) {
			$include = array_filter($include, function($property) use ($collection){
				return !property_exists($collection->first(), $property);
			});
			$collection = $collection->with( $include );
		}
		if ( method_exists( $collection->first(), 'count' ) ) {
			$params = $request->get_query_params();
			if (empty($params['per_page']) || $params['per_page'] <= $collection->total()) {
				if ( ! empty( $params['search'] ) && ! empty( $params['search_fields'] ) ) {
					foreach ( $params['search_fields'] as $field ) {
						$params["{$field}LIKE"] = "%{$params['search']}%";
					}
				}
				$total = $collection->first()::count( $params );
				if ( ! $total == 0 && ! $collection->is_empty() ) {
					$collection->set_total( $total );
				}
			}
		}

		return $this->set_pagination_params( $collection, $request );
	}

	protected function set_pagination_params( Collection $collection, WP_REST_Request $request ) {
		if ( $page = $request->get_param( 'page' ) ) {
			$collection->push_property('page', $page);
		}
		if ( $per_page = $request->get_param( 'per_page' ) ) {
			$collection->push_property('per_page', $per_page);
		}
		if ( $page && $per_page ) {
			if ( $page == 1 && $collection->total() < $per_page ) {
				return $collection;
			}
			$route = $request->get_route();
			foreach ( $request->get_query_params() as $key => $value ) {
				$route = add_query_arg( $key, $value, $route );
			}
			if ($page * $per_page < $collection->total()) {
				$collection->push_property( 'next_page', rest_url( add_query_arg( 'page', $page + 1, $route ) ) );
			}
			if ( $page != 1 ) {
				$collection->push_property( 'prev_page', rest_url( add_query_arg( 'page', $page - 1, $route ) ) );
			}
		}

		return $collection;
	}
}