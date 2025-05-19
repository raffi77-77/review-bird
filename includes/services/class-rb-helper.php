<?php

namespace Review_Bird\Includes\Services;

use Review_Bird\Includes\Exceptions\Review_Bird_Exception;
use ReflectionClass;

class Helper {

	public static function underscore_to_camelcase( $string ) {
		$words = explode( '_', $string );

		return ucfirst( implode( '', array_map( 'ucfirst', $words ) ) );
	}

	public static function camelcase_to_underscore( $string, $separator = '_' ) {
		// Use a regular expression to replace camel case with underscore
		$output = preg_replace_callback( '/([a-z])([A-Z])/', function ( $matches ) use ( $separator ) {
			return $matches[1] . $separator . strtolower( $matches[2] );
		}, $string );

		return strtolower( $output );
	}

	public static function underscore_to_hyphen( $string, $lowercase = false ) {
		$string = str_replace( '_', '-', $string );

		return $lowercase ? strtolower( $string ) : $string;
	}

	public static function hyphen_to_underscore( $string, $lowercase = false ) {
		$string = str_replace( '-', '_', $string );

		return $lowercase ? strtolower( $string ) : $string;
	}

	public static function get_wp_error( $e ) {
		if ( $e instanceof Review_Bird_Exception ) {
			return new \WP_Error( $e->get_error_code(), $e->getMessage(), $e->get_error_data() );
		}

		return new \WP_Error( 'technical_error', __( 'Technical error', 'sr-rb' ), [ 'http_status' => 400 ] );
	}

	public static function get_class_shortname( $className, $lowercase = false ) {
		if ( is_object( $className ) ) {
			$reflection = new ReflectionClass( $className );
			$name       = $reflection->getShortName();
		} else {
			$className = is_object( $className ) ? get_class( $className ) : $className;
			$className = explode( '\\', $className );
			$name      = end( $className );
		}
		if ( $lowercase ) {
			$name = strtolower( $name );
		}

		return $name;
	}

	public static function get_namespace_name( $class ): ?string {
		try {
			return ( new ReflectionClass( $class ) )->getNamespaceName();
		} catch ( \Exception $e ) {
			self::log( $e, __METHOD__ );

			return null;
		}
	}

	public static function log( $data, $key = 'log' ): void {
		if ( Review_Bird()->get_debug() ) {
			$key = (string) $key;
			if ( $data instanceof Review_Bird_Exception ) {
				$data_to_log = $data->get_error_data();
			} else {
				$data_to_log = $data;
			}
			if ( $data instanceof \Exception ) {
				$message = $data->getMessage() . ':  ';
			}
			error_log( Review_Bird()->get_plugin_name() . '-debug: [' . $key . ']: ' . ( $message ?? '' ) . print_r( $data_to_log, true ) );
		}
	}

	public static function get_uuid() {
		return wp_generate_uuid4();
	}

	public static function get_random_string() {
		return wp_generate_password( 10, false );
	}

	public static function maybe_json_decode( $string, $assoc = true ) {
		if ( ! is_string( $string ) ) {
			return $string;
		}
		$decoded = json_decode( $string, $assoc );
		if ( json_last_error() == JSON_ERROR_NONE ) {
			return $decoded;
		} else {
			return $string;
		}
	}

	public static function is_valid_url( $url ) {
		if ( ! str_starts_with( $url, 'http://' ) && ! str_starts_with( $url, 'https://' ) ) {
			return false;
		}
		if ( ! filter_var( $url, FILTER_VALIDATE_URL ) ) {
			return false;
		}

		return true;
	}

	public static function is_valid_base64_image( $base64 ) {
		// Check if the string starts with the correct data URL format
		if ( preg_match( '/^data:image\/(\w+);base64,/', $base64, $matches ) ) {
			// Extract the actual base64 data part
			$data = substr( $base64, strpos( $base64, ',' ) + 1 );
			// Check if the remaining string has valid base64 characters and optional padding
			if ( preg_match( '/^[A-Za-z0-9+\/]*={0,2}$/', $data ) ) {
				// Decode a portion to check for image signatures
				$decodedData = base64_decode( $data, true );
				if ( $decodedData === false ) {
					return false; // Decoding failed
				}
				// Check image type based on signature
				$imageType = strtolower( $matches[1] );
				switch ( $imageType ) {
					case 'png':
						return str_starts_with( $decodedData, "\x89PNG\r\n\x1a\n" );
					case 'jpeg':
						return str_starts_with( $decodedData, "\xFF\xD8\xFF" ); // JPEG signature
					case 'gif':
						return str_starts_with( $decodedData, "GIF89a" ) || str_starts_with( $decodedData, "GIF87a" ); // GIF signature
					// Add more image formats as needed
					default:
						return false; // Unsupported image type
				}
			}
		}

		return false; // Invalid format
	}

	public static function get_wp_object( int $object_id, string $object_type ) {
		if ( $object_type === 'post' ) {
			return get_post( $object_id );
		} elseif ( $object_type === 'term' ) {
			return get_term( $object_id );
		}

		return null;
	}

	public static function get_instance_property_value( $instance, string $property_name ) {
		try {
			$reflection = new ReflectionClass( $instance );
			$property   = $reflection->getProperty( $property_name );
			$property->setAccessible( true );

			return $property->getValue( $instance );
		} catch ( \Exception $e ) {
			return null;
		}
	}

	public static function cast_value( string $type, $value ) {
		switch ( $type ) {
			case 'int':
				return (int) $value;
			case 'float':
				return (float) $value;
			case 'string':
				return (string) $value;
			case 'bool':
				return filter_var( $value, FILTER_VALIDATE_BOOLEAN );
			case 'array':
				return (array) $value;
			case 'object':
				return (object) $value;
			case 'Limb_Chatbot\Includes\Services\Collection':
				return $value;
			default:
				return null;
		}
	}

	public static function gettype( $value ): string {
		if ( is_int( $value ) ) {
			return 'int';
		}
		if ( is_bool( $value ) ) {
			return 'bool';
		}
		if ( is_float( $value ) ) {
			return 'float';
		}
		if ( is_string( $value ) ) {
			return 'string';
		}
		if ( is_array( $value ) ) {
			return 'array';
		}
		if ( is_object( $value ) ) {
			return 'object';
		}

		return gettype( $value );
	}

}
