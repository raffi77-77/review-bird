<?php

namespace Review_Bird\Includes\Services;

use Review_Bird\Includes\Data_Objects\Flow;
use Review_Bird\Includes\Data_Objects\Review;
use Review_Bird\Includes\Repositories\Review_Repository;
use Review_Bird\Includes\Utilities\Flow_Utility;

class Review_Service {
	protected Review_Repository $review_repository;

	public function __construct() {
		$this->review_repository = new Review_Repository();
	}

	public function create( array $data ): ?Review {
		$flow            = Flow::find_by_uuid( $data['flow_uuid'] );
		$flow_utility    = new FLow_Utility( $flow );
		$data['flow_id'] = $flow->id;
		if ( $data['like'] ) {
			$target_index   = count( $flow_utility->targets ) > 1 ? $flow_utility->calc_target_index( $flow_utility->target_distribution ) : 0;
			$data['target'] = $flow_utility->targets[ $target_index ] ?? null;
		}
		$review = $this->review_repository->create( $data );
		if ( $review && ! $review->like && $flow_utility->email_notify_on_negative_review && ! empty( $flow_utility->emails_on_negative_review ) ) {
			foreach ( $flow_utility->emails_on_negative_review as $email ) {
				if ( ! empty( $email ) ) {
					$this->email_notify( $email, $review, $flow );
				}
			}
		}

		return $review;
	}

	public function update( array $where, array $data ): ?Review {
		return $this->review_repository->update( $where, $data );
	}

	public function email_notify( string $user_email, Review $review, Flow $flow ) {
		$site_name = get_bloginfo( 'name' );
		$subject    = sprintf( __( 'New Review Notification in - %s', 'review-bird' ), $site_name );
		$stars_html = $review->rating ? str_repeat( '⭐', $review->rating ) : __( 'N/A', 'review-bird' );
		$like_svg   = $review->like ? '👍' : '👎';
		$username   = $review->username ?? __( 'N/A', 'review-bird' );
		$review_text   = $review->message ?? __( 'N/A', 'review-bird' );
		ob_start();
		include Review_Bird()->get_plugin_dir_path() . 'templates/email-notification.php';
		$message = ob_get_clean();
		$headers    = [
			"Content-Type: text/html; charset=UTF-8",
			"From: {$site_name} <info@limb.dev>",
		];
		$sent       = wp_mail( $user_email, $subject, $message, $headers );
		Helper::log( [], '#' . $review->id . ' - Email notification is' . ( $sent ? ' sent' : ' not sent' ) );
	}
}