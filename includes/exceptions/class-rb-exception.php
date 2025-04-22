<?php

namespace Review_Bird\Includes\Exceptions;

use Exception as Base_Exception;

class Exception extends Base_Exception {

	protected $error_code;
	protected array $error_data = [];
	protected ?int $http_status = null;

	public function __construct( $error_code, $message, $data = array(), $http_status = null ) {
		$this->error_code  = $error_code;
		$this->error_data  = $data;
		$this->http_status = $http_status;
		parent::__construct( $message );
	}

	public function get_error_code() {
		return $this->error_code;
	}

	public function get_error_data(): array {
		return $this->error_data;
	}

	public function get_http_status(): ?int {
		return $this->http_status;
	}
}