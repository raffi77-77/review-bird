<?php

namespace Review_Bird\Includes\Admin\Pages\Review;

use Review_Bird\Includes\Cpts\Flow\Custom_Post_Type;
use Review_Bird\Includes\Data_Objects\Flow;
use Review_Bird\Includes\Data_Objects\Review;
use WP_List_Table;

class Table extends WP_List_Table {

	protected array $printed_flows = array();

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
			'id'         => __( 'ID', 'review-bird' ),
			'username'   => __( 'Username', 'review-bird' ),
			'message'    => __( 'Message', 'review-bird' ),
			'flow'       => __( 'Flow', 'review-bird' ),
			'like'       => __( 'Answer', 'review-bird' ),
			'rating'     => __( 'Rating', 'review-bird' ),
			'created_at' => __( 'Submission Date', 'review-bird' ),
		];
	}

	public function get_sortable_columns() {
		return [
			'id'         => [ 'id', true ],
			'flow'       => [ 'flow_id', true ],
			'like'       => [ 'like', true ],
			'rating'     => [ 'rating', true ],
			'created_at' => [ 'created_at', true ],
		];
	}

	protected function column_cb( $item ): string {
		return sprintf( '<input type="checkbox" name="review[]" value="%s" />', $item->id );
	}

	protected function column_default( $item, $column_name ): string {
		return $item->{$column_name} ?? '';
	}

	protected function column_rating( Review $item ): string {
		$stars = str_repeat( '⭐', (int) $item->rating );

		return $item->rating ? $stars : __('N/A', 'review-bird');
	}

	public function prepare_items() {
		$columns               = $this->get_columns();
		$hidden                = [];
		$sortable              = $this->get_sortable_columns();
		$this->_column_headers = [ $columns, $hidden, $sortable ];
		$per_page              = 10;
		$current_page          = $this->get_pagenum();
		$orderby               = sanitize_text_field( $_REQUEST['orderby'] ?? 'id' );
		$order                 = sanitize_text_field( $_REQUEST['order'] ?? 'desc' );
		$search                = sanitize_text_field( $_REQUEST['s'] ?? '' );
		$flow_id               = absint( $_REQUEST['flow_id'] ?? 0 );
		$rating                = is_numeric( $_REQUEST['rating'] ?? '' ) ? (int) $_REQUEST['rating'] : '';
		$search && $where['messageLIKE'] = $search;
		$flow_id && $where['flow_id'] = $flow_id;
		$rating && $where['rating'] = $rating;
		$total_items = Review::count( $where ?? [] );
		$this->items = Review::where( $where ?? [], $per_page, $current_page, $orderby, $order );
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

	public function display_filters(): void {
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

	public function column_ID( Review $item ): string {
		$edit_link = add_query_arg( [ 'id' => $item->id ], menu_page_url( Page::$menu_slug, false ) );

		return sprintf( '<a href="%s"><strong>%d</strong></a>', esc_url( $edit_link ), $item->id );
	}

	public function column_flow( Review $item ): void {
		if ( isset( $this->printed_flows[ $item->flow_id ]['flow'] ) ) {
			$flow      = $this->printed_flows[ $item->flow_id ]['flow'];
			$thumbnail = $this->printed_flows[ $item->flow_id ]['thumbnail'] ?? null;
		} else {
			$flow         = $this->printed_flows[ $item->flow_id ]['flow'] = Flow::find( $item->flow_id );
			$thumbnail_id = $flow ? $flow->get_meta( '_thumbnail_id' ) : null;
			$thumbnail    = $this->printed_flows[ $item->flow_id ]['thumbnail'] = $thumbnail_id ? wp_get_attachment_thumb_url( $thumbnail_id ) : null;
		}
		if ( $flow ) {
			?>
            <a style="display: flex; align-items: center;" href="<?= esc_attr( get_edit_post_link( $flow->get_id() ) ) ?>">
				<?= esc_html( $flow->get_title() ) ?>
				<?php if ( $thumbnail ): ?>
                    <img style="width: 50px; height: auto; margin-left: 5px" src="<?= esc_attr( $thumbnail ) ?>">
				<?php endif; ?>
            </a>
			<?php
		}
	}
    
    public function column_like( Review $item ): void {
        if($item->like) {
            ?>
            <?php
        } else {
            ?>
            <?php
        }
    }
}

