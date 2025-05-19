<?php

namespace Review_Bird\Includes\Services;

use Review_Bird\Includes\Scheme_Interface;

class Validator {
	private Scheme_Interface $schema_class;
	protected array $validated;
	protected array $errors;

	public function __construct( Scheme_Interface $schema_class ) {
		$this->schema_class = $schema_class;
	}

	public function validate( array $data ): bool {
		$this->errors    = [];
		$this->validated = [];
		foreach ( $this->schema_class->rules() as $key => $rule ) {
			$value = $data[ $key ] ?? null;
			if ( ! empty( $rule['required'] ) && ( $value === null || $value === '' ) ) {
				$this->errors[ $key ][] = __( 'Field is required.', 'review-bird' );
				continue;
			}
			if ( is_null( $value ) ) {
				continue;
			}
			if ( isset( $rule['validate_callback'] ) && is_callable( $rule['validate_callback'] ) ) {
				if ( ! call_user_func( $rule['validate_callback'], $value ) ) {
					$this->errors[ $key ][] = __( 'Invalid value.', 'review-bird' );
					continue;
				}
			}
			if ( isset( $rule['enum'] ) && ! in_array( $value, $rule['enum'], true ) ) {
				$this->errors[ $key ][] = sprintf( __( 'Must be one of: %s', 'review-bird' ), implode( ', ', $rule['enum'] ) );
				continue;
			}
			if ( isset( $rule['type'] ) ) {
				if ( ! $this->validate_type( $value, $rule['type'], $rule ) ) {
					$this->errors[ $key ][] = sprintf( __( "Expected type %s.", 'review-bird' ), $rule['type'] );
					continue;
				}
			}
			if ( ! isset( $this->errors[ $key ] ) ) {
				$this->validated[ $key ] = $value;
			}
		}

		return ! empty( $this->errors );
	}

	private function validate_type( $value, string $type, array $rule ): bool {
		switch ( $type ) {
			case 'string':
				return is_string( $value );
			case 'int':
				return is_int( $value );
			case 'float':
				return is_float( $value );
			case 'bool':
				return is_bool( $value );
			case 'array':
				if ( ! is_array( $value ) ) {
					return false;
				}
				if ( isset( $rule['items']['type'] ) ) {
					foreach ( $value as $item ) {
						if ( Helper::gettype( $item ) !== $rule['items']['type'] ) {
							return false;
						}
					}
				}

				return true;
			case 'object':
				return is_object( $value );
			default:
				return true;
		}
	}

	public function get_validated(): array {
		return $this->validated;
	}

	public function get_errors(): array {
		return $this->errors;
	}
}