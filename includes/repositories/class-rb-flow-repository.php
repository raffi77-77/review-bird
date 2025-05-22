<?php

namespace Review_Bird\Includes\Repositories;

use Review_Bird\Includes\Data_Objects\Flow;
use Review_Bird\Includes\Data_Objects\Flow_Meta;
use Review_Bird\Includes\Services\Helper;
use WP_Post;

class Flow_Repository {

	public function get_item( $id ) {
		return is_int( $id ) ? Flow::find( $id ) : Flow::find_by_uuid( $id );
	}

	public function get_items( ?array $params = [] ) {
		return Flow::where( $params );
	}

	public function create( int $id, array $data ): ?Flow {
		$flow = Flow::find( $id );
		Flow_Meta::create( [ 'flow_id' => $id, 'meta_key' => '_uuid', 'meta_value' => Helper::get_uuid() ] );
		if ( ! empty( $data['metas'] ) ) {
			foreach ( $data['metas'] as $key => $value ) {
				Flow_Meta::create( [ 'flow_id' => $id, 'meta_key' => $key, 'meta_value' => $value ] );
			}
		}

		return $flow;
	}

	public function update( int $id, array $data ): ?Flow {
		$flow = Flow::find( $id );
		if ( ! empty( $data['metas'] ) ) {
			foreach ( $data['metas'] as $key => $value ) {
				Flow_Meta::update( [ 'flow_id' => $id, 'meta_key' => $key ], [ 'meta_value' => $value ] );
			}
		}

		return $flow;
	}

	public function save_post( int $id, WP_Post $post, array $data ) {
		if ( $post->post_status === 'publish' ) {
			if ( empty( Flow_Meta::where( [ 'flow_id' => $id, 'meta_key' => '_uuid' ] )->first() ) ) {
				return $this->create( $id, $data );
			}
		}

		return $this->update( $id, $data );
	}
}