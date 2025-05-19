<?php

namespace Review_Bird\Includes\Data_Objects;

use Exception;
use Review_Bird\Includes\Database_Strategies\WP_Meta_Query;
use Review_Bird\Includes\Services\Helper;

class WP_Meta_Data_Object extends Data_Object {

	public $post_id;
	public $id;
	public $meta_key;
	public $meta_value;

	public function __construct( $instance = null ) {
		if ( is_array( $instance ) && ! empty( $instance['meta_value'] ) && $this->isJson( $instance['meta_value'] ) ) {
			$instance['meta_value'] = Helper::maybe_json_decode( $instance['meta_value'] );
		}
		parent::__construct( $instance );
	}

    public function save() {
		$data          = (array) $this;
		$data          = array_map( function ( $item ) {
			if ( ! is_array( $item ) && ! is_object( $item ) ) {
				return $item;
			} else {
				return json_encode( $item );
			}
		}, $data );
		$should_create = empty( $this->id );
		$should_create = self::where( [
				'post_id'  => $this->post_id,
				'meta_key' => $this->meta_key
			] )->is_empty() && $should_create;
		if ( $should_create ) {
			$this->set_data( static::get_db_strategy()->create( $data ) );
		} else {
			$where = [ 'post_id' => $this->post_id, 'meta_key' => $this->meta_key ];
			$this->set_data( static::get_db_strategy()->update( $where, $data ) );
		}
		$this->hydrate();
	}

	public static function create( $data ) {
		return self::update( [ 'post_id' => $data['post_id'], 'meta_key' => $data['meta_key'] ], $data );
	}

	public static function update( $where, $data ) {
		static::get_db_strategy()->update( $where, $data );
		// Basically it's hard to count on update method result, cause when item is failed to update or duplication sent it's the same case
		$metas = self::where( [ 'post_id' => $where['post_id'], 'meta_key' => $where['meta_key'] ] );
		if ( ! $metas->is_empty() ) {
			return $metas->first();
		}
		throw new Exception( __( 'Failed to update meta!', 'review-bird' ) );
	}

	public static function get_db_strategy() {
		return WP_Meta_Query::instance();
	}

	public function get_meta_key() {
		return $this->meta_key;
	}

	public function set_meta_key( $meta_key ): void {
		$this->meta_key = $meta_key;
	}

	public function get_meta_value() {
		return $this->meta_value;
	}

	public function set_meta_value( $meta_value ): void {
		$this->meta_value = $meta_value;
	}

	public function toArray(int $depth = 0): array {
		$parentToArray = parent::toArray();
		unset( $parentToArray['id'] );

		return $parentToArray + [
				'post_id' => $this->get_post_id()
			];
	}

	/**
	 * @return mixed
	 */
	public function get_post_id() {
		return $this->post_id;
	}

	/**
	 * @param mixed $post_id
	 */
	public function set_post_id( $post_id ): void {
		$this->post_id = $post_id;
	}
}