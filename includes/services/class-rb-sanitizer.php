<?php

namespace Review_Bird\Includes\Services;

use Review_Bird\Includes\Scheme_Interface;

class Sanitizer {
	private Scheme_Interface $schema_class;

	public function __construct( Scheme_Interface $schema_class ) {
		$this->schema_class = $schema_class;
	}
	
	public function sanitize( array $data ): array {
		$result = [];
		foreach ( $this->schema_class::rules() as $key => $rule ) {
			if ( ! array_key_exists( $key, $data ) ) {
				continue;
			}
			$value = $data[ $key ];
			if ( isset( $rule['sanitize_callback'] ) && is_callable( $rule['sanitize_callback'] ) ) {
				$value = call_user_func( $rule['sanitize_callback'], $value );
			} elseif ( isset( $rule['type'] ) ) {
				$value = $this->sanitize_by_type( $value, $rule['type'] );
			}
			$result[ $key ] = $value;
		}

		return $result;
	}

	private function sanitize_by_type( $value, string $type ) {
		switch ( $type ) {
			case 'string':
				return sanitize_text_field( $value );
			case 'int':
				return (int) $value;
			case 'float':
				return (float) $value;
			case 'bool':
				return (bool) $value;
			case 'array':
				return is_array( $value ) ? array_map( 'sanitize_text_field', $value ) : [];
			default:
				return $value;
		}
	}
}