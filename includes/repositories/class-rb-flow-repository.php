<?php

namespace Review_Bird\Includes\Repositories;

use Review_Bird\Includes\Data_Objects\Flow;
use Review_Bird\Includes\Data_Objects\Flow_Meta;
use Review_Bird\Includes\Services\Helper;

class Flow_Repository {

	public function get_item( $id ) {
		return is_int( $id ) ? Flow::find( $id ) : Flow::find_by_uuid( $id );
	}

	public function get_items( ?array $params = [] ) {
		return Flow::where( $params );
	}

	public function create( int $id, array $data ) {
		$flow = Flow::find( $id );
		if ( empty( $flow->get_meta( 'uuid' ) ) ) {
			Flow_Meta::create( [ 'flow_id' => $id, 'meta_key' => '_uuid', 'meta_value' => Helper::get_uuid() ] );
		}
		if ( ! empty( $data['metas'] ) ) {
			foreach ( $data['metas'] as $key => $value ) {
				Flow_Meta::create( [ 'flow_id' => $id, 'meta_key' => $key, 'meta_value' => $value ] );
			}
		}

		return $flow;
	}

	public function update( int $id, array $data ) {
		$flow = Flow::find( $id );
		if ( ! empty( $data['metas'] ) ) {
			foreach ( $data['metas'] as $key => $value ) {
				Flow_Meta::update( [ 'flow_id' => $id, 'meta_key' => $key ], [ 'meta_value' => $value ] );
			}
		}

		return $flow;
	}
}