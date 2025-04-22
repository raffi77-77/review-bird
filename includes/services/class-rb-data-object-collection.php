<?php

namespace Review_Bird\Includes\Services;

use ReflectionClass;

class Data_Object_Collection extends Collection {
	public function with( $relations, $args = [] ) {
		if ( $this->is_empty() || empty( $relations ) ) {
			return $this;
		}
		if ( is_array( $relations ) ) {
			foreach ( $relations as $item ) {
				$this->include_property( $item, $args );
			}
		} else {
			$this->include_property( $relations, $args );
		}

		return $this;
	}

	private function include_property( $relation, $args ) {
		$data_object = new ReflectionClass( get_class( $this->first() ) );
		if ( method_exists( $data_object->getName(), $relation ) ) {
			foreach ( $this->items as $key => $item ) {
				if (property_exists( $this->items[ $key ], 'included' ) ) {
					$this->items[ $key ]->included[$relation] = $this->items[ $key ]->{$relation}( $args );
				}
			}
		}
	}
}