<?php

namespace Review_Bird\Includes\Repositories;

use Review_Bird\Includes\Data_Objects\Setting;
use Review_Bird\Includes\Exceptions\Exception;

class Setting_Repository {
	public function get_items( $params = [] ) {
		// Key param check
		$where['option_name'] = ! empty( $params['key'] ) && is_array( $params['key'] ) ? array_map( function ( $item ) {
			return Setting::sanitize_key( $item );
		}, $params['key'] ) : ( ! empty( $params['key'] ) ? Setting::sanitize_key( $params['key'] ) : null );
		// Group param check
		if ( empty( $where['option_name'] ) && ! empty( Setting::sanitize_key( $params['group'] ) ) ) {
			$where['option_nameLIKE'] = Setting::sanitize_key( $params['group'] ) . '%';
		}
		if ( is_null( $where['option_name'] ) ) {
			unset( $where['option_name'] );
		}

		return Setting::where( $where )->get();
	}

	public function update( $key, $data ) {
		// $data can be filtered via wp filters
		$setting = Setting::update( [ 'key' => $key ], [ 'value' => $data['value'] ] );
		if ( ! is_a( $setting, Setting::class ) ) {
			throw new Exception( 0, __( 'Failed to create setting', 'review-bird' ) );
		}
		do_action( "{$setting->get_key()}_updated", $setting );

		return $setting;
	}

	public function create( $params ) {
		// $params can be filtered via wp filters
		$setting = Setting::create( $params );
		if ( ! is_a( $setting, Setting::class ) ) {
			throw new Exception( 0, __( 'Failed to create setting', 'review-bird' ) );
		}
		do_action( "{$setting->get_key()}_updated", $setting );

		return $setting;
	}

	public function delete( $params ) {
		return Setting::delete( [ 'key' => $params['key'] ] );
	}
}