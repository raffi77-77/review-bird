<?php

namespace Review_Bird\Includes\Cpts\Flow;

use Review_Bird\Includes\Scheme_Interface;

class Meta_Scheme implements Scheme_Interface {
	public string $question = 'Would you recommend {site-name} to others?';
	public array $targets = [];
	public bool $enable_multiple_targets = false;
	public string $negative_review_text = '';
	public string $field_name_placeholder = '';
	public string $field_review_placeholder = '';
	public string $success_message = '';
	public bool $review_gating = false;

	public static function rules(): array {
		return [
			'question'                 => [
				'type'     => 'string',
				'required' => true,
			],
			'enable_multiple_targets'  => [
				'type' => 'boolean',
			],
			'negative_review_text'     => [
				'type' => 'string',
			],
			'field_name_placeholder'   => [
				'type' => 'string',
			],
			'field_review_placeholder' => [
				'type' => 'string',
			],
			'success_message'          => [
				'type' => 'string',
			],
			'review_gating'            => [
				'type' => 'boolean',
			],
			'targets'                  => [
				'type'              => 'object',
				'sanitize_callback' => [ self::class, 'sanitize_targets' ],
				'validate_callback' => [ self::class, 'validate_targets' ],
			],
		];
	}

	public static function sanitize_targets( $value ) {
		return $value;
	}

	public static function validate_targets( $value ): bool {
		// $value instanceof Some_Collection
		return $value;
	}
}