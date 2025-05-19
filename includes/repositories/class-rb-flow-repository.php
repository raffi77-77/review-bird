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
	
	public function create( array $data ) {
		
	}
	
	public function update( int $id, array $data ) {
		Flow_Meta::create([ 'post_id' => $id, 'meta_key' => '_uuid', 'meta_value' => Helper::get_uuid() ]);
	}
}