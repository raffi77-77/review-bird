<?php

namespace Review_Bird\Includes\Data_Objects;

use Review_Bird\Includes\Database_Strategies\WP_Query;
use Review_Bird\Includes\Utilities\Flow_Utility;

class Flow extends WP_Post_Data_Object {
	const POST_TYPE = 'review_bird_flow';

	/**
	 * @json_excluded
	 */
	public $id;
	public ?string $uuid = null;
	protected array $meta_properties = [ 'metas', 'utility' ];

	/**
	 * @json_excluded
	 */
	public array $included = [];

	public static function find_by_uuid( $uuid ): ?Flow {
		$flows = self::get_db_strategy()->find_by_meta( '_uuid', $uuid, self::POST_TYPE );
		$flow  = null;
		if ( ! empty( $flows[0] ) ) {
			$flow       = self::make( $flows[0] );
			$flow->uuid = $uuid;
		}

		return $flow;
	}

	public static function exists_by_uuid( $uuid ) {
		$chatbots = self::get_db_strategy()->find_by_meta( '_uuid', $uuid, self::POST_TYPE );

		return ! empty( $chatbots[0] );
	}

	public static function get_db_strategy() {
		return WP_Query::instance();
	}

	public static function find( $id ) {
		$post = self::get_db_strategy()->find( $id, self::POST_TYPE );

		return ! empty( $post ) ? self::make( $post ) : null;
	}

	public function metas() {
		return Flow_Meta::where( [ 'post_id' => $this->id, 'meta_key' => [ '_uuid' ] ] )->get();
	}

	public function utility() {
		return new Flow_Utility( $this );
	}

}