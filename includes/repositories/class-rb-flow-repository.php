<?php

namespace Review_Bird\Includes\Repositories;

use Review_Bird\Includes\Data_Objects\Flow;

class Flow_Repository {

	public function get_item( $id, ?array $params = [] ) {
		return is_int( $id ) ? Flow::find( $id ) : Flow::find_by_uuid( $id );
	}

	public function get_items( ?array $params = [] ) {
		return Flow::where( $params );
	}
}