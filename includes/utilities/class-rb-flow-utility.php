<?php

namespace Review_Bird\Includes\utilities;

use JsonSerializable;
use Review_Bird\Includes\Data_Objects\Flow;
use Review_Bird\Includes\Data_Objects\Flow_Meta;
use Review_Bird\Includes\Services\Collection;
use Review_Bird\Includes\Services\Helper;
use Review_Bird\Includes\Traits\Json_Serializable_Trait;
use ReflectionClass;

class Flow_Utility implements JsonSerializable {

	use Json_Serializable_Trait;
	
	/**
	 * @json_excluded
	 */
	public Flow $flow;
	public string $question = 'Would you recommend {site-name} to others?';
	public array $targets = [];
	public bool $enable_multiple_targets = false;
	public string $negative_review_text = '';
	public string $field_name_placeholder = '';
	public string $field_review_placeholder = '';
	public string $success_message = '';
	public bool $review_gating = false;
	
	
	// public ?Collection $some_collection = null;

	public function __construct( Flow $flow ) {
		$this->flow = $flow;
		$this->populate_properties();
	}

	public function populate_properties() {
		$reflection = new ReflectionClass( $this );
		$properties = $reflection->getProperties();
		foreach ( $properties as $property ) {
			if ( $property->getDeclaringClass()->getName() !== $reflection->getName() ) {
				// If not own property, pass it.
				continue;
			}
			if ( ! $property->getType() || (! $property->getType()->isBuiltin() && !$property->getType()->getName() == Collection::class ) ) {
				// If not built in property type, pass it.
				continue;
			}
			$property_name = $property->getName();
			// From Cpt
			if ( isset( $this->flow->{$property_name} ) ) {
				$value = $this->flow->{$property_name};
			}
			$value = empty( $value ) ? $this->flow->get_meta($property_name) : $value;
			// Otherwise leave as it is
			if ( isset( $value ) ) {
				$this->{$property_name} = Helper::cast_value( $property->getType()->getName(), $value );
			}
			unset( $value );
		}
	}
}
