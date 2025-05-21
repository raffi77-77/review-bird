<?php

namespace Review_Bird\Includes\Data_Objects;

class Flow_Meta extends WP_Meta_Data_Object {
	public static function create( $data ) {
		$data['post_id'] = $data['flow_id'] ?? $data['post_id'];
		unset( $data['flow_id'] );

		return parent::create( $data );
	}

	public static function update( $where, $data ) {
		$where['post_id'] = $where['flow_id'] ?? $where['post_id'];
		unset( $where['flow_id'] );

		return parent::update( $where, $data );
	}   

	public static function where( $where, ...$args ) {
		if ( isset( $where['flow_id'] ) ) {
			$where['post_id'] = $where['flow_id'];
			unset( $where['flow_id'] );
		}

		return parent::where( $where, $args );
	}
}