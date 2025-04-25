<?php

namespace Review_Bird\Includes\Admin;

use Review_Bird\Includes\Cpts\Flow\Custom_Post_Type;
use Review_Bird\Includes\Data_Objects\Review;
use Review_Bird\Includes\Services\Helper;
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
			'username'   => 'Username',
			'message'    => 'Message',
			'like'       => 'Answer',
			'rating'     => 'Rating',
			'created_at' => 'Submission Date'
		];
	}

	protected function column_cb( $item ) {
		return sprintf( '<input type="checkbox" name="review[]" value="%s" />', $item->id );
	}

	protected function column_default( $item, $column_name ) {
		return $item->{$column_name} ?? '';
	}

	public function prepare_items() {
		$columns               = $this->get_columns();
		$hidden                = [];
		$sortable              = [];
		$this->_column_headers = [ $columns, $hidden, $sortable ];
		$per_page              = 10;
		$current_page          = $this->get_pagenum();
		$search                = sanitize_text_field( $_REQUEST['s'] ?? '' );
		$flow_id               = absint( $_REQUEST['flow_id'] ?? 0 );
		$rating                = is_numeric( $_REQUEST['rating'] ?? '' ) ? (int) $_REQUEST['rating'] : '';
		$search && $where['messageLIKE'] = $search;
		$flow_id && $where['flow_id'] = $flow_id;
		$rating && $where['rating'] = $rating;
		$total_items = Review::count( $where ?? [] );
		$this->items = Review::where( $where ?? [], $per_page, $current_page );
		$this->set_pagination_args( [
			'total_items' => $total_items,
			'per_page'    => $per_page,
			'total_pages' => ceil( $total_items / $per_page ),
		] );
	}

	public function display() {
		?>
        <div class="wrap">
            <h1 class="wp-heading-inline"><?= __( 'Reviews', 'review-bird' ) ?></h1>
            <form method="get">
				<?php
				$this->display_filters();
				parent::display();
				?>
            </form>
        </div>
		<?php
	}

	public function display_filters() {
		?>
        <div class="tablenav top">
            <div class="alignleft actions">
                <select name="flow_id">
                    <option value=""><?= __( 'All Flows', 'review-bird' ) ?></option>
					<?php foreach ( get_posts( [ 'post_type' => Custom_Post_Type::NAME, 'numberposts' => - 1 ] ) as $flow ) { ?>
                        <option value="<?= esc_attr( $flow->ID ) ?>" <?= esc_attr( selected( $_GET['flow_id'] ?? '', $flow->ID, false ) ) ?>><?= esc_html( $flow->post_title ) ?></option>
					<?php } ?>
                </select>

                <select name="rating">
                    <option value=""><?= __( 'All Ratings', 'review-bird' ) ?></option>
					<?php for ( $i = 1; $i <= 5; $i ++ ) { ?>
                        <option value="<?= esc_attr( $i ) ?>" <?= esc_attr( selected( $_GET['rating'] ?? '', $i, false ) ) ?>><?= esc_html( $i ) ?> <?= __( 'Stars', 'review-bird' ) ?></option>
					<?php } ?>
                </select>
				<?php submit_button( 'Filter', '', '', false ); ?>
            </div>

			<?php $this->search_box( 'Search Reviews', 'review_search' ); ?>
        </div>
		<?php
	}
}

