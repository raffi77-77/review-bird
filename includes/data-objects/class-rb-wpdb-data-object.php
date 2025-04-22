<?php

namespace Review_Bird\Includes\Data_Objects;

use Review_Bird\Includes\Database_Strategies\WPDB;
use Review_Bird\Includes\Services\Data_Object_Collection;
use Review_Bird\Includes\Services\Helper;

class WPDB_Data_Object extends Data_Object {

	public ?int $id = null;

	public static function where( $where, ...$args ) {
		$id = $where['id'] ?? null;
		// Filter only fillable attributes, but always allow 'id'
		$where = array_filter( $where, function ( $value, $key ) {
			return static::is_fillable( $key );
		}, ARRAY_FILTER_USE_BOTH );
		// Re-add 'id' manually if it was part of the query
		if ( $id !== null ) {
			$where['id'] = $id;
		}
		// Get table name using the new centralized method
		array_unshift( $args, static::TABLE_NAME );
		// Execute query and transform results into objects
		$results = static::get_db_strategy()->where( $where, ...$args );
		foreach ( $results as $result ) {
			$item = static::make( $result );
			$objects[] = $item;
		}

		return new Data_Object_Collection( $objects ?? [] );
	}

	protected static function is_fillable( $key ) {
		$key = preg_replace( '/(>=|<=|>|<|!=|=|LIKE)$/', '', $key );

		return defined( 'static::FILLABLE' ) && in_array( $key, static::FILLABLE );
	}

	static function get_db_strategy() {
		return WPDB::instance();
	}

	public static function delete( $where ) {
		foreach ( $where as $key => $value ) {
			if ( is_array( $value ) || is_object( $value ) ) {
				$where[ $key ] = json_encode( $value );
			}
		}

		return static::get_db_strategy()->delete( $where, static::TABLE_NAME );
	}

	public static function count( $where = [], ...$args ) {
		if ( isset( $where['id'] ) ) {
			$id = $where['id'];
		}
		if ( defined( 'static::FILLABLE' ) ) {
			$where = array_filter( $where, function ( $value, $key ) {
				return in_array( $key, static::FILLABLE );
			}, ARRAY_FILTER_USE_BOTH );
		}
		// Above fillable check was designed to avoid DB errors of unknown columns.
		// But below id case should exist anyway if requested. That's why setting back manually.
		if ( isset( $id ) ) {
			$where['id'] = $id;
		}

		return static::get_db_strategy()->count( $where, static::TABLE_NAME, ...$args );
	}

	/**
	 * @return static|null
	 */
	public static function find( $id ) {
		if ( empty( $id ) ) {
			return null;
		}

		$res = static::get_db_strategy()->find( $id, static::TABLE_NAME );

		return ! empty( $res ) ? static::make( $res ) : null;
	}

	public function get_id() {
		return $this->id;
	}

	public function set_id( $id ) {
		$this->id = $id;
	}

	public function save() {
		$data = (array) $this;
		$data = array_map( function ( $item ) {
			if ( ! is_array( $item ) && ! is_object( $item ) ) {
				return $item;
			} else {
				return json_encode( $item );
			}
		}, $data );
		if ( empty( $this->id ) ) {
			$this->set_data( static::get_db_strategy()->create( $data, static::TABLE_NAME, static::FILLABLE ) );
			$this->hydrate();
		} else {
			$this->set_data( static::get_db_strategy()->update( [ 'id' => $this->id ],
				$data,
				static::TABLE_NAME,
				static::FILLABLE ) );
			$this->hydrate();
		}
	}

	/**
	 * @return null|static
	 * @throws \Exception
	 */
	public static function create( $data ) {
		foreach ( $data as $key => $value ) {
			if ( is_object( $key ) || is_array( $value ) ) {
				$data[ $key ] = json_encode( $value );
			}
		}
		$res =  self::get_db_strategy()->create( $data, static::TABLE_NAME, static::FILLABLE );

		return static::make( $res );
	}

	/**
	 * @param $where
	 * @param $data
	 *
	 * @return static|null
	 */
	public static function update( $where, $data ) {
		foreach ( $data as $key => $value ) {
			if ( is_array( $key ) || is_array( $value ) ) {
				$data[ $key ] = json_encode( $value );
			}
		}
		$res = static::get_db_strategy()->update( $where, $data, static::TABLE_NAME, static::FILLABLE );

		return static::make( $res );
	}
}