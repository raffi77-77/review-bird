<?php

namespace Review_Bird\Includes\Data_Objects;

class Review extends WPDB_Data_Object {

	const TABLE_NAME = 'review_bird_reviews';
	const FILLABLE = array( 'uuid', 'flow_id', 'flow_uuid', 'message', 'user_id', 'username', 'rating', 'like', 'target', 'created_at', 'updated_at' );
	public string $uuid;
	/**
	 * @json_excluded 
	 */
	public int $flow_id;
	public string $flow_uuid;
	public ?string $message = null;
	public ?int $user_id = null;
	public ?string $username = null;
	public ?int $rating = null;
	public ?bool $like = null;
	public ?string $target = null;
	public string $created_at;
	public string $updated_at;
	
	/**
	 * @json_excluded
	 */
	public array $included = [];

}