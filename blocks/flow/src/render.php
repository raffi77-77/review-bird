<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */
$flow_id   = $attributes['flow_id'] ? get_post_meta( $attributes['flow_id'], '_uuid', true ) : false;
$flow_attributes = json_encode( $attributes ); // $flow_id ? get_post_meta( $flow_id ) : null;

ob_start();
?>
    <div <?php echo get_block_wrapper_attributes(); ?>>
        <div class="review-bird-flow" data-flow_id="<?php echo esc_attr( $flow_id ); ?>"
             data-flow_attributes="<?php echo esc_attr( $flow_attributes ); ?>"></div>
    </div>
<?php
$block_content = ob_get_clean();

echo wp_kses_post( $block_content );
