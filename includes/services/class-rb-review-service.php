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

		return $this->review_repository->create( $data );
	}

	public function update( array $where, array $data ): ?Review {
		return $this->review_repository->update( $where, $data );
	}
}