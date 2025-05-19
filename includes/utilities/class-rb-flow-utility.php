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
	const TARGET_DISTRIBUTIONS
		= [
			1  => [ 50, 50 ],
			2  => [ 60, 40 ],
			3  => [ 70, 30 ],
			4  => [ 80, 20 ],
			5  => [ 90, 10 ],
			6  => [ 33, 33, 34 ],
			7  => [ 50, 25, 25 ],
			8  => [ 60, 25, 15 ],
			9  => [ 70, 20, 10 ],
			10 => [ 80, 15, 5 ],
			11 => [ 25, 25, 25, 25 ],
			12 => [ 40, 20, 20, 20 ],
			13 => [ 50, 20, 15, 15 ],
			14 => [ 60, 20, 10, 10 ],
			15 => [ 70, 15, 10, 5 ],
		];

	/**
	 * @json_excluded
	 */
	public Flow $flow;
	public string $question = 'Would you recommend {site-name} to others?';
	public array $targets
		= [
			[
				'url' => 'gago.com',
				'media_id' => null
			],
			[
				'url' => 'vaxo.com',
				'media_id' => null
			],
			[
				'url' => 'petros.com',
				'media_id' => null
			],
			[
				'url' => 'antuan.com',
				'media_id' => null
			]
		];
	public ?int $target_distribution = 15;
	public bool $enable_multiple_targets = true;
	public string $negative_review_text = '';
	public string $field_name_placeholder = '';
	public string $field_review_placeholder = '';
	public string $success_message = '';
	public bool $review_gating = false;
	public bool $sent_email_on_negative_review = false;
	public string $email_on_negative_review = '';


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
			if ( ! $property->getType() || ( ! $property->getType()->isBuiltin() && ! $property->getType()->getName() == Collection::class ) ) {
				// If not built in property type, pass it.
				continue;
			}
			$property_name = $property->getName();
			// From Cpt
			if ( isset( $this->flow->{$property_name} ) ) {
				$value = $this->flow->{$property_name};
			}
			$value = empty( $value ) ? $this->flow->get_meta( $property_name ) : $value;
			// Otherwise leave as it is
			if ( isset( $value ) ) {
				$this->{$property_name} = Helper::cast_value( $property->getType()->getName(), $value );
			}
			unset( $value );
		}
	}

	public function calc_target_index( ?int $distribution_index ) {
		$distribution_index = $distribution_index ?? 0;
		$distribution = self::TARGET_DISTRIBUTIONS[ $distribution_index ];
		$total        = array_sum( $distribution );
		$rand         = rand( 1, $total );
		$cumulative   = 0;
		foreach ( $distribution as $index => $weight ) {
			$cumulative += $weight;
			if ( $rand <= $cumulative ) {
				return $index;
			}
		}

		return 0;
	}
}
