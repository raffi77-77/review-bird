<?php

namespace Review_Bird\Includes\Cpts\Flow;

use Review_Bird\Includes\Scheme_Interface;
use Review_Bird\Includes\Utilities\Flow_Utility;

class Meta_Scheme implements Scheme_Interface {

	public static function rules(): array {
		return [
			'question'                        => [
				'type'     => 'string',
				'required' => true,
			],
			'targets'                         => [
				'type'              => 'array',
				'required'          => true,
				'sanitize_callback' => [ self::class, 'sanitize_targets' ],
				'validate_callback' => [ self::class, 'validate_targets' ],
			],
			'target_distribution'             => [
				'type'              => 'integer',
				'validate_callback' => [ self::class, 'validate_target_distribution' ],
			],
			'multiple_targets'                => [
				'type' => 'boolean',
			],
			'review_box_text'                 => [
				'type' => 'string',
			],
			'username_placeholder'            => [
				'type' => 'string',
			],
			'review_placeholder'              => [
				'type' => 'string',
			],
			'success_message'                 => [
				'type' => 'string',
			],
			'gating'                          => [
				'type' => 'boolean',
			],
			'email_notify_on_negative_review' => [
				'type' => 'boolean',
			],
			'emails_on_negative_review'       => [
				'type'              => 'array',
				'sanitize_callback' => [ self::class, 'sanitize_emails_on_negative_review' ],
				'validate_callback' => [ self::class, 'validate_emails_on_negative_review' ],
			]
		];
	}

	public static function sanitize_targets( $value ) {
		if ( is_array( $value ) ) {
			foreach ( $value as &$item ) {
				$item['url']      = is_string( $item['url'] ) ? sanitize_url( $item['url'] ) : null;
				$item['media_id'] = is_numeric( $item['media_id'] ) ? (int) ( $item['media_id'] ) : null;
			}
		}

		return empty( $value ) ? [] : $value;
	}

	public static function validate_targets( $value ): bool {
		$valid = true;
		if ( is_array( $value ) ) {
			foreach ( $value as $item ) {
				if ( ! filter_var( $item['url'], FILTER_VALIDATE_URL ) || ( ! empty( $item['media_id'] ) && empty( wp_get_attachment_url( $item['media_id'] ) ) ) ) {
					$valid = false;
					break;
				}
			}
		} else {
			return false;
		}

		return $valid;
	}

	public static function validate_target_distribution( $value ): bool {
		return $value === 0 || array_key_exists( $value, Flow_Utility::TARGET_DISTRIBUTIONS );
	}

	public static function validate_emails_on_negative_review( $value ): bool {
		$valid = true;
		if ( is_array( $value ) ) {
			foreach ( $value as $item ) {
				if ( ! filter_var( $item, FILTER_VALIDATE_EMAIL ) ) {
					$valid = false;
				}
			}
		} else {
			return false;
		}

		return $valid;
	}

	public static function sanitize_emails_on_negative_review( $value ) {
		if ( is_array( $value ) ) {
			foreach ( $value as &$item ) {
				$item = is_string( $item ) ? sanitize_email( $item ) : null;
			}
		}

		return empty( $value ) ? [] : $value;
	}
}