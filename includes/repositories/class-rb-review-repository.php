<?php

namespace Review_Bird\Includes\Repositories;

use Review_Bird\Includes\Data_Objects\Review;
use Review_Bird\Includes\Exceptions\Error_Codes;
use Review_Bird\Includes\Exceptions\Exception;
use Review_Bird\Includes\Services\Helper;

class Review_Repository {

	public function get_item( $chatbot_id, ?array $params = [] ) {
		return is_int( $chatbot_id ) ? Chatbot::find( $chatbot_id ) : Chatbot::find_by_uuid( $chatbot_id );
	}

	public function get_items( ?array $params = [] ) {
		return Chatbot::where( $params );
	}
	
	public function create() {
		return Review::make();
	}
}