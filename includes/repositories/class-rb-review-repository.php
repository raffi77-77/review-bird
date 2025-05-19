<?php

namespace Review_Bird\Includes\Repositories;

use Review_Bird\Includes\Data_Objects\Review;

class Review_Repository {
	public function create( array $data ) {
		return Review::create( $data );
	}
	
	public function update(array $where, array $data ) {
		return Review::update( $where, $data );
	}
}