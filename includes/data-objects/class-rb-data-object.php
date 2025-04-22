<?php

namespace Review_Bird\Includes\Data_Objects;

use JsonSerializable;
use Review_Bird\Includes\Database_Strategy_Interface;
use Review_Bird\Includes\Services\Data_Object_Collection;
use Review_Bird\Includes\Traits\Json_Serializable_Trait;
use ReflectionClass;
use stdClass;
use WP_Post;

abstract class Data_Object implements JsonSerializable {

	use Json_Serializable_Trait;

	const UUID_COLUMN_NAME = 'uuid';
	protected $data;

	public function __construct( $instance = null ) {
		if ( is_numeric( $instance ) ) {
			$this->set_id( $instance );
		} elseif ( $instance instanceof WP_Post || is_array( $instance ) || $instance instanceof stdClass ) {
			$this->set_data( $instance );
			$this->hydrate();
		}
		// Allowing new Data_Object without $instance argument.
	}

	public function set_id( $id ) {
		$this->id = $id;
	}

	protected function hydrate() {
		if ( is_array( $this->data ) || $this->data instanceof stdClass ) {
			// From array
			foreach ( $this->data as $key => $value ) {
				if ( property_exists( $this, $key ) ) {
					$setter = 'set' . '_' . strtolower( $key );
					if ( method_exists( $this, $setter ) ) {
						call_user_func( [ $this, $setter ], $value );
					} else {
						$this->{$key} = $value;
					}
				}
			}
		} elseif ( $this->data instanceof WP_Post ) {
			// From WP_Post
			$this->id         = $this->data->ID;
			$this->title      = $this->data->post_title;
			$this->created_at = $this->data->post_date;
			$this->updated_at = $this->data->post_modified;
			if ( property_exists( $this, 'uuid' ) ) {
				$this->uuid = method_exists( $this, 'get_meta' ) ? $this->get_meta( self::UUID_COLUMN_NAME ) : null;
			}
			if ( property_exists( $this, 'status' ) ) {
				$this->status = WP_Post_Data_Object::get_our_status_equivalent( $this->data->post_status );
			}
		}
	}

	public static function create( $data ) {
		foreach ( $data as $key => $value ) {
			if ( is_object( $key ) || is_array( $value ) ) {
				$data[ $key ] = json_encode( $value );
			}
		}
		$res = static::get_db_strategy()->create( $data );

		return static::make( $res );
	}

	/**
	 * @return  Database_Strategy_Interface
	 */
	abstract static function get_db_strategy();

	public static function make( $data = [] ) {
		return new static( $data );
	}

	public static function find( $id ) {
		return static::make(static::get_db_strategy()->find( $id, static::TABLE_NAME ));
	}

	public static function update( $where, $data ) {
		return static::make( static::get_db_strategy()->update( $where, $data ) );
	}

	public static function delete( $where ) {
		return static::get_db_strategy()->delete( $where );
	}

	public static function where( $where, ...$args ) {
		$results = static::get_db_strategy()->where( $where, ...$args );
		foreach ( $results as $result ) {
			$objects[] = static::make( $result );
		}

		return new Data_Object_Collection( $objects ?? [] );
	}

	public static function count( $where = [], ...$args ) {
		return static::get_db_strategy()->count( $where, ...$args );
	}

	/**
	 * @return mixed
	 */
	public function get_data() {
		return $this->data;
	}

	/**
	 * @param mixed $data
	 */
	public function set_data( $data ) {
		$this->data = $data;
	}

	/**
	 * @return mixed
	 */
	public function get_id() {
		return $this->id;
	}

	public function __get( $name ) {
		$getter = 'get_' . strtolower( $name );
		if ( method_exists( $this, $getter ) ) {
			return call_user_func( [ $this, $getter ] );
		}
		if ( property_exists( $this, $name ) ) {
			return $this->$name;
		}
		if ( isset( $this->$name ) ) {
			return $this->$name;
		}

		return null;
	}

	public function __set( $name, $value ) {
		$setter = 'set' . '_' . strtolower( $name );
		if ( method_exists( $this, $setter ) ) {
			call_user_func( [ $this, $setter ], $value );
		}
		$this->$name = $value;
	}

	public function refresh( $source ) {
		$reflection = new ReflectionClass( $this );
		if ( get_class( $source ) != static::class ) {
			return;
		}
		if ( $this !== $source ) {
			// Only if it's not the same object
			foreach ( $reflection->getProperties() as $property ) {
				if ( in_array( $property->getName(), [ 'id', 'data' ] ) ) {
					continue;
				}
				$property->setAccessible( true );
				// $current_value = $property->isInitialized( $this ) ? $property->getValue( $this ) : null;
				$new_value = $property->isInitialized( $source ) ? $property->getValue( $source ) : null;
				// isInitialized check is mainly required for typedProperties
				if ( $property->isInitialized( $source ) && ! empty( $new_value ) ) {
					$property->setValue( $this, $new_value );
				}
			}
		}
	}
}