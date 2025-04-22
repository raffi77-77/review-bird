<?php

namespace Review_Bird\Includes\Exceptions;

class Error_Codes {

	// 10000 - 10099: Validation errors.
	// Errors related to invalid inputs, incorrect formats, or missing required values
	const VALIDATION_BAD_BASE64 = 10006;  // Bad base64 format error
	const VALIDATION_UNSUPPORTED_MIME_TYPE = 10007;  // Unsupported mime type error
	const VALIDATION_VALUE_EXISTS = 10008;  // Value already exists error
	const VALIDATION_INVALID_VALUE = 10009; // Invalid value error
	const VALIDATION_REQUIRED = 10010; // Required value missing error
}
