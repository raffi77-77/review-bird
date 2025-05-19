<?php

namespace Review_Bird\Includes\Services;

use Review_Bird\Includes\Scheme_Interface;

class Validator {
	private Scheme_Interface $schema_class;

	public function __construct( Scheme_Interface $schema_class ) {
		$this->schema_class = $schema_class;
	}

	public function validate( array $data ): array {
		$errors = [];
		foreach ( $this->schema_class->rules() as $key => $rule ) {
			$value = $data[ $key ] ?? null;
			if ( ! empty( $rule['required'] ) && ( $value === null || $value === '' ) ) {
				$errors[ $key ][] = __( 'Field is required.', 'review-bird' );
				continue;
			}
			if ( $value === null ) {
				continue;
			}
			if ( isset( $rule['validate_callback'] ) && is_callable( $rule['validate_callback'] ) ) {
				if ( ! call_user_func( $rule['validate_callback'], $value ) ) {
					$errors[ $key ][] = __( 'Invalid value.', 'review-bird' );
				}
			}
			if ( isset( $rule['enum'] ) && ! in_array( $value, $rule['enum'], true ) ) {
				$errors[ $key ][] = sprintf( __( 'Must be one of: %s', 'review-bird' ), implode( ', ', $rule['enum'] ) );
			}
			if ( isset( $rule['type'] ) ) {
				if ( ! $this->validate_type( $value, $rule['type'], $rule ) ) {
					$errors[ $key ][] = sprintf( __( "Expected type %s.", 'review-bird' ), $rule['type'] );
				}
			}
		}

		return $errors;
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
						if ( gettype( $item ) !== $rule['items']['type'] ) {
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
}