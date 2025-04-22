<?php

namespace Review_Bird\Includes\Services;

use Countable;
use IteratorAggregate;
use ArrayIterator;
use JsonSerializable;

class Collection implements Countable, IteratorAggregate, JsonSerializable {

	public array $items;
	public int $total;

	protected $included;

	public function __construct( array $items ) {
		$this->items = $items;
		$this->set_total( count( $items ) );
	}

	public function set_total( int $count ): void {
		$this->total = $count;
	}

	public function total(): int {
		return $this->total;
	}

	public function first() {
		return ! $this->is_empty() ? $this->items[0] : null;
	}

	public function is_empty(): bool {
		return empty( $this->items );
	}

	public function last() {
		return ! $this->is_empty() ? $this->items[ count( $this->items ) - 1 ] : null;
	}

	public function count(): int {
		return count( $this->items );
	}

	public function get(): array {
		return $this->items;
	}

	public function each( callable $callback ): self {
		foreach ( $this->items as $item ) {
			$callback( $item );
		}

		return $this;
	}

	public function map( callable $callback ): self {
		return new static( array_map( $callback, $this->items ) );
	}

	public function filter( callable $callback ): self {
		return new static( array_values( array_filter( $this->items, $callback ) ) );
	}

	public function reduce( callable $callback, $initial ) {
		return array_reduce( $this->items, $callback, $initial );
	}

	public function getIterator(): ArrayIterator {
		return new ArrayIterator( $this->items );
	}

	public function contains( $value ): bool {
		return in_array( $value, $this->items, true );
	}

	public function unique(): self {
		return new static( array_values( array_unique( $this->items, SORT_REGULAR ) ) );
	}

	public function merge( array $items ): self {
		return new static( array_merge( $this->items, $items ) );
	}

	public function push_item( $item, $key = '' ): self {
		if ( $key ) {
			$this->items[ $key ] = $item;
		} else {
			$this->items[] = $item;
		}

		return $this;
	}

	public function push_property( $key, $property ) {
		$this->included[ $key ] = $property;

		return $this;
	}

	#[\ReturnTypeWillChange]
	public function jsonSerialize() {
		$array = [
			'items' => $this->items,
			'total' => $this->total,
		];
		if ( ! empty( $this->included ) ) {
			foreach ( $this->included as $key => $property ) {
				$array[ $key ] = $this->included[ $key ];
			}
		}

		return $array;
	}
}
