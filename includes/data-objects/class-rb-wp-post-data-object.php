<?php

namespace Review_Bird\Includes\Data_Objects;

use Review_Bird\Includes\Database_Strategies\WP_Query;

class WP_Post_Data_Object extends Data_Object {
	const STATUS_PUBLISHED = 1;
	const STATUS_DRAFT = 3;
	public $id;
	public $title;
	public ?int $status = null;
	public $created_at;
	public $updated_at;

	public static function get_our_status_equivalent( $status ) {
		return array_flip( self::wp_statuses_equivalents() )[ $status ];
	}

	protected static function wp_statuses_equivalents() {
		return [
			self::STATUS_PUBLISHED => 'publish',
			self::STATUS_DRAFT     => 'draft',
		];
	}

	public static function get_statuses() {
		return [
			self::STATUS_PUBLISHED,
			self::STATUS_DRAFT,
		];
	}

	public static function count( $where = [], ...$args ) {
		unset( $where['page'] );
		unset( $where['per_page'] );

		return parent::count( static::map_where( $where ), static::POST_TYPE );
	}

	protected static function map_where( $where ) {
		if ( isset( $where['status'] ) ) {
			$where['post_status'] = self::get_wp_status_equivalent( $where['status'] );
			unset( $where['status'] );
		} else {
			$where['post_status'] = 'any';
		}
		// In case there is a data which is not wp_post table related, then it should be through meta
		if ( isset( $where['uuid'] ) ) {
			$where['meta_query'] = [
				[
					'key'     => '_' . self::UUID_COLUMN_NAME,
					'value'   => $where['uuid'],
					'compare' => 'IN',
				],
			];
			unset( $where['uuid'] );
		}
		if ( isset( $where['page'] ) ) {
			$where['paged'] = $where['page'];
			unset( $where['page'] );
		}
		if ( isset( $where['per_page'] ) ) {
			$where['posts_per_page'] = $where['per_page'];
			unset( $where['per_page'] );
		}

		return $where;
	}

	public static function get_wp_status_equivalent( $status ) {
		return self::wp_statuses_equivalents()[ $status ];
	}

	public static function get_db_strategy() {
		return WP_Query::instance();
	}

	public function get_meta( $meta_key, $object = false ) {
		$metas    = WP_Meta_Data_Object::where( [ 'post_id' => $this->get_id(), 'meta_key' => $meta_key ] );
		if ( ! $metas->is_empty() ) {
			return $object ? $metas->first() : $metas->first()->get_meta_value();
		}

		return null;
	}

	public static function where( $where, ...$args ) {
		return parent::where( self::map_where( $where ), static::POST_TYPE );
	}

}