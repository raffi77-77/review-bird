<?php

namespace Review_Bird\Includes\Cpts\Flow;

use Review_Bird\Includes\Traits\SingletonTrait;

class Custom_Post_Type {
	use SingletonTrait;

	public $name = 'review_bird_flow';

	public function register() {
		register_post_type( $this->name, array(
			'label'               => __( 'Flows', 'review-bird' ),
			'name'                => $this->name,
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
		$this->add_meta_boxes();
	}

	protected function add_meta_boxes() {
		add_action( 'add_meta_boxes', array( $this, 'chatbot_meta_box' ) );
	}

	public function chatbot_meta_box() {
		add_meta_box( 'bla-bla-meta-box', 'Bla bla box', function () {
			echo 'bla bla box';
		}, $this->name, 'normal', 'high' );
	}
}