<?php

namespace Review_Bird\Includes\Data_Objects;

class Review extends WPDB_Data_Object {

	const TABLE_NAME = 'wp_sr_rb_reviews';
	const FILLABLE = array( 'uuid', 'flow_id', 'message', 'user_id', 'user_name', 'rating', 'like', 'created_at', 'updated_at' );
	public string $uuid;
	public int $flow_id;
	public ?string $message = null;
	public ?int $user_id = null;
	public ?string $user_name = null;
	public ?int $rating = null;
	public ?bool $like = null;
	public string $created_at;
	public string $updated_at;

	/**
	 * @json_excluded
	 */
	public array $included = [];

}