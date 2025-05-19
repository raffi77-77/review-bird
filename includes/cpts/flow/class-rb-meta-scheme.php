<?php

namespace Review_Bird\Includes\Cpts\Flow;

use Review_Bird\Includes\Scheme_Interface;
use Review_Bird\Includes\utilities\Flow_Utility;

class Meta_Scheme implements Scheme_Interface {
	public string $question = 'Would you recommend {site-name} to others?';
	public array $targets = [];
	public ?int $target_distribution = null;
	public bool $enable_multiple_targets = false;
	public string $negative_review_text = 'Would you recommend {site-name} to others?';
	public string $field_name_placeholder = '';
	public string $field_review_placeholder = '';
	public string $success_message = '';
	public bool $review_gating = false;
	public bool $sent_email_on_negative_review = false;
	public string $email_on_negative_review = '';

	public static function rules(): array {
		return [
			'question'                      => [
				'type'     => 'string',
				'required' => true,
			],
			'targets'                       => [
				'type'              => 'object',
				'required'          => true,
				'sanitize_callback' => [ self::class, 'sanitize_targets' ],
				'validate_callback' => [ self::class, 'validate_targets' ],
			],
			'target_distribution'           => [
				'type'              => 'integer',
				'validate_callback' => [ self::class, 'validate_targets' ],
			],
			'enable_multiple_targets'       => [
				'type' => 'boolean',
			],
			'negative_review_text'          => [
				'type' => 'string',
			],
			'field_name_placeholder'        => [
				'type' => 'string',
			],
			'field_review_placeholder'      => [
				'type' => 'string',
			],
			'success_message'               => [
				'type' => 'string',
			],
			'review_gating'                 => [
				'type' => 'boolean',
			],
			'sent_email_on_negative_review' => [
				'type' => 'boolean',
			],
			'email_on_negative_review'      => [
				'type' => 'email',
			]
		];
	}

	public static function sanitize_targets( $value ): array {
		if ( is_array( $value ) ) {
			foreach ( $value as &$item ) {
				$item = sanitize_url( $item );
			}
		}
		
		return $value;
	}

	public static function validate_targets( $value ): bool {
		if ( is_array( $value ) ) {
			foreach ( $value as $item ) {
				if ( ! filter_var( $item, FILTER_VALIDATE_URL ) ) {
					$valid = false;
					break;
				}
			}
		}

		return $valid ?? false;
	}

	public static function validate_target_distribution( $value ): bool {
		return in_array( Flow_Utility::TARGET_DISTRIBUTIONS, $value, true );
	}
}