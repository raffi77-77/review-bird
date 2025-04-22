<?php

namespace Review_Bird\Includes\Database_Strategies;

use Review_Bird\Includes\Database_Strategy_Interface;
use Review_Bird\Includes\Traits\SingletonTrait;

class WP_Options extends Database_Strategy implements Database_Strategy_Interface {

	use SingletonTrait;

	public function create( $data ) {
		if ( ! isset( $data['value'] ) || ! isset( $data['key'] ) ) {
			return false;
		}
		$existing_value = get_option( $data['key'] );
		if ( $existing_value === false ) {
			if ( add_option( $data['key'], $data['value'] ) ) {
				return [ 'key' => $data['key'], 'value' => get_option( $data['key'], null ) ];
			} else {
				return false;
			}
		}

		return [ 'key' => $data['key'], 'value' => $existing_value ];
	}

	public function update( $where, $data ) {
		if ( ! isset( $data['value'] ) || ! isset( $where['key'] ) ) {
			return false;
		}
		$existing_value = get_option( $where['key'] );
		if ( $existing_value === false ) {
			return $this->create( [ 'key' => $where['key'], 'value' => $data['value'] ] );
		} elseif ( $existing_value === $data['value'] ) {
			return [ 'key' => $where['key'], 'value' => $existing_value ];
		}
		if ( update_option( $where['key'], $data['value'] ) ) {
			return [ 'key' => $where['key'], 'value' => get_option( $where['key'], null ) ];
		}

		return false;
	}

	public function find( $id ) {
		return [ 'key' => $id, 'value' => get_option( $id, null ) ];
	}

	public function where( $conditions, ...$args ) {
		$for_unique_default = md5( 'default' );
		if ( array_key_exists( 'option_nameLIKE', $conditions ) ) {
			$results = ( new WPDB() )->where( $conditions, 'options' );
			foreach ( $results as $result ) {
				$filtered_results[] = [ 'key' => $result['option_name'], 'value' => maybe_unserialize( $result['option_value'] ) ];
			}

			return $filtered_results ?? [];
		} elseif ( isset( $conditions['option_name'] ) && is_array( $conditions['option_name'] ) ) {
			foreach ( $conditions['option_name'] as $key ) {
				$value = get_option( $key, $for_unique_default );
				if ( $value !== $for_unique_default ) {
					$filtered_results[] = [ 'key' => $key, 'value' => $value ];
				}
			}

			return $filtered_results ?? [];
		} else {
			$value = get_option( $conditions['option_name'], $for_unique_default );

			return $value !== $for_unique_default ? [ 'key' => $conditions['option_name'], 'value' => $value ] : null;
		}
	}

	public function count( $conditions, ...$args ) {
	}

	public function delete( $where ) {
		return delete_option( $where['key'] );
	}
}