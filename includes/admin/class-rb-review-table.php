<?php

namespace Review_Bird\Includes\Admin;

use Review_Bird\Includes\Traits\SingletonTrait;
use WP_List_Table;

class Review_Table extends WP_List_Table {

	public function __construct() {
		parent::__construct( [
			'singular' => 'review',
			'plural'   => 'reviews',
			'ajax'     => false,
		] );
	}

	public function get_columns() {
		return [
			'cb'         => '<input type="checkbox" />',
			'id'         => 'ID',
			'created_at' => 'Submission Date',
			'message'    => 'Message',
			'answer'     => 'Answer',
			'rating'     => 'Rating',
		];
	}

	protected function column_cb( $item ) {
		return sprintf( '<input type="checkbox" name="review[]" value="%s" />', $item['id'] );
	}

	protected function column_default( $item, $column_name ) {
		return $item[ $column_name ] ?? '';
	}

	public function prepare_items() {
		global $wpdb;
		$columns               = $this->get_columns();
		$hidden                = [];
		$sortable              = [];
		$this->_column_headers = [ $columns, $hidden, $sortable ];
		$per_page     = 10;
		$current_page = $this->get_pagenum();
		$flow_id = $_GET['flow_id'] ?? null;
		// SQL base
		$where  = 'WHERE 1=1';
		$params = [];
		if ( ! empty( $flow_id ) ) {
			$where    .= ' AND r.flow_id = %d';
			$params[] = $flow_id;
		}
		$sql = "
            SELECT 
                r.id,
                r.created_at,
                r.message,
                r.rating,
                a.value AS answer
            FROM {$wpdb->prefix}sr_rb_reviews r
            INNER JOIN {$wpdb->prefix}posts p ON r.flow_id = p.ID
            INNER JOIN {$wpdb->prefix}sr_rb_questions q ON q.flow_id = r.flow_id
            LEFT JOIN {$wpdb->prefix}sr_rb_answers a 
                ON a.review_id = r.id AND a.question_id = q.id
            $where
            ORDER BY r.created_at DESC
        ";
		$prepared_sql = $wpdb->prepare( $sql, $params );
		$data         = $wpdb->get_results( $prepared_sql, ARRAY_A );
		$total_items = count( $data );
		$data        = array_slice( $data, ( $current_page - 1 ) * $per_page, $per_page );
		$this->items = $data;
		$this->set_pagination_args( [
			'total_items' => $total_items,
			'per_page'    => $per_page,
			'total_pages' => ceil( $total_items / $per_page ),
		] );
	}

	public function display() {
		echo '<div class="wrap"><h1 class="wp-heading-inline">Reviews</h1>';
		echo '<form method="get">';
		echo '<input type="hidden" name="page" value="' . esc_attr($_REQUEST['page']) . '" />';
		parent::display();
		echo '</form>';
		echo '</div>';
	}

	public function show_filters() {

	}
}

