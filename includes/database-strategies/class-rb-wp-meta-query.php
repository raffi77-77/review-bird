<?php

namespace Review_Bird\Includes\Database_Strategies;

use Review_Bird\Includes\Database_Strategy_Interface;
use Review_Bird\Includes\Services\Collection;
use Review_Bird\Includes\Traits\SingletonTrait;

class WP_Meta_Query extends Database_Strategy implements Database_Strategy_Interface {

	use SingletonTrait;

	const TABLE_NAME = 'postmeta';
	const FILLABLE = [ 'id', 'post_id', 'meta_key', 'meta_value', ];

	public function create( $data ) {
		return $this->update( [ 'post_id' => $data['post_id'], 'meta_key' => $data['meta_key'] ], $data );
	}

	public function find( $id ) {
	}

	public function where( $conditions = array(), $table = null ) {
		// Default arguments
		$defaults = array(
			'meta_key'   => '',
			'meta_value' => '',
			'post_id'    => 0
		);
		// Merge provided arguments with defaults
		$args = wp_parse_args( $conditions, $defaults );
		// post_id existence is required
		if ( empty( $args['post_id'] ) ) {
			return [];
		}
		// Extract arguments
		$meta_key   = $args['meta_key'];
		$meta_value = $args['meta_value'];
		$post_id    = $args['post_id'];
		// Check if both meta_key and meta_value are provided
		if ( ! empty( $meta_key ) && ! empty( $meta_value ) ) {
			$meta_keys = is_array( $meta_key ) ? $meta_key : [ $meta_key ];
			foreach ( $meta_keys as $meta_key ) {
				// Get metadata with specific key and value
				foreach ( get_post_meta( $post_id, $meta_key ) as $item ) {
					if ( $item == $meta_value ) {
						$results[] = [ 'meta_value' => $meta_value, 'meta_key' => $meta_key, 'post_id' => $post_id ];
					}
				}
			}
		} elseif ( ! empty( $meta_key ) ) {
			$meta_keys = is_array( $meta_key ) ? $meta_key : [ $meta_key ];
			foreach ( $meta_keys as $meta_key ) {
				// Get meta metadata with specific key
				$meta_values = get_post_meta( $post_id, $meta_key );
				foreach ( $meta_values as $meta_value ) {
					$results[] = [ 'meta_value' => $meta_value, 'meta_key' => $meta_key, 'post_id' => $post_id ];
				}
			}
		} else {
			// Get all metadata data for the post
			$filtered_metas = get_post_meta( $post_id );
			foreach ( $filtered_metas as $key => $filtered_meta ) {
				$results[] = [ 'meta_value' => $filtered_meta[0], 'meta_key' => $key, 'post_id' => $post_id ];
			}
		}

		return $results ?? [];
	}

	public function count( $conditions ) {
	}

	public function delete( $where ) {
	}

	public function update( $where, $data ) {
		if ( isset( $data['meta_value'] ) && ( is_array( $data['meta_value'] ) || is_object( $data['meta_value'] ) || $data['meta_value'] instanceof Collection ) ) {
			$data['meta_value'] = json_encode( $data['meta_value'] );
		}

		return update_post_meta( $where['post_id'], $where['meta_key'], $data['meta_value'] );
	}
}