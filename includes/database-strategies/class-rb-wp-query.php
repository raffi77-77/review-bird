<?php

namespace Review_Bird\Includes\Database_Strategies;

use Review_Bird\Includes\Database_Strategy_Interface;
use Review_Bird\Includes\Traits\SingletonTrait;

class WP_Query extends Database_Strategy implements Database_Strategy_Interface {

	use SingletonTrait;

	public function create( $data, $table = null, $fillable = null, $uuid_column = null ) {
		$id = wp_insert_post( $data );
		if ( $id && ! is_wp_error( $id ) ) {
			return get_post( $id );
		} else {
			throw new \Exception( __( 'Error on creating post.', 'limb-ai' ) );
		}
	}

	public function find( $id, ...$args ) {
		$posts = get_posts( [
			'p'           => $id,
			'post_type'   => $args[0],
			'post_status' => 'any',
		] );

		return $posts[0] ?? null; // Get the first (and only) post
	}

	public function delete( $where ) {
		if ($id = $where['id']){
			return wp_delete_post( $id, true );
		}

		return false;
	}

	public function update( $where, $data ) {
		if ( $id = $where['id'] ) {
			return wp_update_post( array_merge( [ 'ID' => $id ], $data ) );
		}

		return false;
	}

	public function find_by_meta( $meta_key, $meta_value, $post_type ) {
		return self::where( [
			'meta_query' => [
				[
					'key'     => $meta_key,
					'value'   => $meta_value,
					'compare' => '=',
				],
			]
		], $post_type );
	}

	public function where( $conditions = array(), ...$args ): array {
		$posts = new \WP_Query( array_merge( $conditions, [ 'post_type' => $args[0] ] ) );
		$items = $posts->get_posts();
		wp_reset_postdata();

		return $items;
	}

	public function count( $conditions, ...$args ) {
		$post_type = $args[0];
		$count_obj = wp_count_posts( $post_type );

		return !empty($count_obj->publish) ? (int) $count_obj->publish : 0;
	}
}