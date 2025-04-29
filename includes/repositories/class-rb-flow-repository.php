<?php

namespace Review_Bird\Includes\Repositories;

use Review_Bird\Includes\Data_Objects\Flow;
use Review_Bird\Includes\Data_Objects\Flow_Meta;

class Flow_Repository {

	public function get_item( $id, ?array $params = [] ) {

		$flow = is_int( $id ) ? Flow::find( $id ) : Flow::find_by_uuid( $id );
		if ( ! empty( $params['include'] ) && in_array( 'metas', $params['include'] ) ) {
			// TODO setup the correct metas here
			$flow->metas[] = Flow_Meta::where( [ 'post_id' => $flow->id, 'meta_key' => '_uuid' ] )->first();
		}

		return $flow;
	}

	public function get_items( ?array $params = [] ) {
		return Flow::where( $params );
	}
}