<?php

namespace Review_Bird\Includes\Cpts;

class Custom_Post_Types {

	public static function register() {
		add_action( 'init', [ Flow\Custom_Post_Type::instance(), 'register' ] );
	}
}