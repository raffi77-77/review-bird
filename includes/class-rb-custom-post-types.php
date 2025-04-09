<?php

namespace Review_Bird\Includes;

class Custom_Post_Types {

	public static function setup() {
		add_action( 'init', [ self::class, 'register' ] );
	}

	public static function register() {
		register_post_type( 'review_bird_flow', array(
			'label'               => __( 'Flows', 'review-bird' ),
			'name'                => 'review_bird_flow',
			'labels'              => [
				'name'          => __( 'Flows', 'review-bird' ),
				'singular_name' => __( 'Flow', 'review-bird' ),
				'add_new'       => __( 'Add New', 'review-bird' ),
				'add_new_item'  => __( 'Add new flow', 'review-bird' ),
				'edit_item'     => __( 'Edit flow', 'review-bird' ),
				'new_item'      => __( 'New flow', 'review-bird' ),
			],
			'supports'            => array( 'title'/*, 'custom-fields'*/ ),
			'description'         => __( '', 'review-bird' ),
			'public'              => false,
			'show_ui'             => true,
			'map_meta_cap'        => true,
			'publicly_queryable'  => false,
			'exclude_from_search' => true,
			'show_in_menu'        => 'review-bird',
			'hierarchical'        => false,
			'show_in_nav_menus'   => false,
			'rewrite'             => false,
			'query_var'           => false,
			'has_archive'         => false,
			'show_in_rest'        => true,
		) );
	}
}