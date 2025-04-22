<?php

namespace Review_Bird\Includes\Traits;

use ReflectionClass;
use ReflectionProperty;

trait Json_Serializable_Trait {

	#[\ReturnTypeWillChange]
	public function jsonSerialize() {
		return $this->toArray();
	}
	private static array $serialization_stack = [];
	private static int $max_recursion_depth = 10;

	/**
	 * NOTE: this method will include only initially defined properties and not dynamically assigned via magic method __set
	 * TODO predefined properties order (future)
	 *
	 * @param int $depth
	 *
	 * @return array
	 */
	public function toArray( int $depth = 0 ): array {
		// Prevent infinite recursion
		if ( $depth > self::$max_recursion_depth ) {
			return []; // to prevent max recursion depth
		}
		$object_id = spl_object_id( $this );
		if ( isset( self::$serialization_stack[ $object_id ] ) ) {
			return []; // to prevent circular reference
		}
		self::$serialization_stack[ $object_id ] = true;
		$reflection                              = new ReflectionClass( $this );
		$array                                   = [];
		// Get public properties
		$properties = $reflection->getProperties( ReflectionProperty::IS_PUBLIC );
		// Merge with `meta_properties`, ensuring only valid properties are included
		foreach ( $properties as $property ) {
			if ( ! $property instanceof ReflectionProperty ) {
				continue;
			}
			$name = $property->getName();
			// Skip properties marked with @json_excluded
			$doc_comment = $property->getDocComment();
			if ( $doc_comment && str_contains( $doc_comment, '@json_excluded' ) ) {
				continue;
			}
			// Get property value
			$value = isset($this->{$name}) ? $this->{$name} : null;
			// Recursively convert objects that implement toArray()
			if ( is_object( $value ) && method_exists( $value, 'toArray' ) ) {
				$value = $value->toArray( $depth + 1 );
			} elseif ( is_array( $value ) ) {
				$sub_array = [];
				foreach ( $value as $sub_key => $sub_value ) {
					if ( is_object( $sub_value ) && method_exists( $sub_value, 'toArray' ) ) {
						$sub_value = $sub_value->toArray( $depth + 1 );
					}
					$sub_array[ $sub_key ] = $sub_value;
				}
				$value = $sub_array;
			}
			$array[ $name ] = $value;
		}
		if ( property_exists( $this, 'meta_properties' ) ) {
			foreach ( $this->meta_properties as $meta_property ) {
				if ( property_exists( $this, 'included' ) && array_key_exists($meta_property, $this->included) ) {
					$array[$meta_property] = $this->included[$meta_property];
				}
			}
		}
		// Remove object from tracking stack
		unset( self::$serialization_stack[ $object_id ] );

		return $array;
	}

	public function isJson( $string ): bool {
		if ( ! is_string( $string ) ) {
			return false;
		}
		json_decode( $string );

		return ( json_last_error() === JSON_ERROR_NONE );
	}
}