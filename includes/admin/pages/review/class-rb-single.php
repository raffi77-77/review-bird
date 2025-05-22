<?php

namespace Review_Bird\Includes\Admin\Pages\Review;

use Review_Bird\Includes\Data_Objects\Review;

class Single {
	protected $review = null;

	public function __construct( $id ) {
		$this->review = Review::find( $id );
	}

	public function display() {
		?>
        <div class="wrap">
            <h1><?php esc_html_e( 'Review', 'review-bird' ); ?></h1>
			<?php
			$this->render_data();
			?>
        </div>
		<?php
	}

	public function render_data() {
		?>
        <div class="rw-skin-content" style="max-width: 600px;">
            <table class="rw-cont-table">
                <tbody class="rw-cont-table-tbody">
                <tr class="rw-cont-table-in">
                    <th class="rw-cont-table-item-title">
                        <label for="rw-id" class="rw-admin-title-in"><?php _e( "ID", 'review-bird' ); ?></label>
                    </th>
                    <td class="rw-cont-table-item">
                        <input id="rw-id" type="text" class="rw-admin-input"
                               placeholder="<?php _e( 'ID', 'review-bird' ); ?>"
                               value="<?php echo $this->review->id; ?>" disabled/>
                    </td>
                </tr>
                <tr class="rw-cont-table-in">
                    <th class="rw-cont-table-item-title">
                        <label for="rw-liked" class="rw-admin-title-in"><?php _e( "Like", 'review-bird' ); ?></label>
                    </th>
                    <td class="rw-cont-table-item">
                        <div class="rw-admin-row">
                            <div class="rw-admin-row-in">
                                <input id="rw-liked" type="checkbox"
                                       class="rw-admin-row-input"<?php echo $this->review->liked ? ' checked' : ''; ?>
                                       disabled/>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr class="rw-cont-table-in">
                    <th class="rw-cont-table-item-title">
                        <label class="rw-admin-title-in"><?php _e( "User ID", 'review-bird' ); ?></label>
                    </th>
                    <td class="rw-cont-table-item">
                        <?php if ($this->review->user_id): ?>
                            <p class="rw-admin-desc"><?php echo $this->review->user_id; ?>:
                                <a href="<?php echo get_edit_user_link( $this->review->user_id ); ?>"><?php _e( "Edit profile", 'review-bird' ); ?></a>
                            </p>
                        <?php else: ?>
                            <p><?php _e( "N/A", 'review-bird' ); ?></p>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr class="rw-cont-table-in">
                    <th class="rw-cont-table-item-title">
                        <label for="rw-username"
                               class="rw-admin-title-in"><?php _e( "Target", 'review-bird' ); ?></label>
                    </th>
                    <td class="rw-cont-table-item">
                        <p><?php echo $this->review->target ?: __( "N/A", 'review-bird' ); ?></p>
                    </td>
                </tr>
				<tr class="rw-cont-table-in">
                    <th class="rw-cont-table-item-title">
                        <label for="rw-username"
                               class="rw-admin-title-in"><?php _e( "Username", 'review-bird' ); ?></label>
                    </th>
                    <td class="rw-cont-table-item">
                        <input id="rw-username" type="text" class="rw-admin-input"
                               placeholder="<?php _e( 'Username', 'review-bird' ); ?>"
                               value="<?php echo $this->review->username; ?>" disabled/>
                    </td>
                </tr>
                <tr class="rw-cont-table-in">
                    <th class="rw-cont-table-item-title">
                        <label for="rw-user-rating"
                               class="rw-admin-title-in"><?php _e( "Rating", 'review-bird' ); ?></label>
                    </th>
                    <td class="rw-cont-table-item">
                        <?php if ($this->review->rating): ?>
                            <p class="rw-admin-desc"><?php echo str_repeat( '⭐', (int) $this->review->rating ) ?>
                                (<?php echo $this->review->rating; ?>)</p>
                        <?php else: ?>
                            <p><?php _e( "N/A", 'review-bird' ); ?></p>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr class="rw-cont-table-in">
                    <th class="rw-cont-table-item-title">
                        <label for="rw-user-message"
                               class="rw-admin-title-in"><?php _e( "Message", 'review-bird' ); ?></label>
                    </th>
                    <td class="rw-cont-table-item">
                        <div class="rw-admin-row">
							<textarea id="rw-user-message" class="rw-admin-textarea"
                                      placeholder="<?php _e( 'User message', 'review-bird' ); ?>"
                                      rows="3" disabled><?php echo $this->review->message; ?></textarea>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
		<?php
	}
}