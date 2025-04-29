<?php
/**
 * PHP file to use when rendering the block type on the server to show on the front end.
 *
 * The following variables are exposed to the file:
 * @var array $attributes The block attributes.
 * @var string $content The block default content.
 * @var WP_Block $block The block instance.
 *
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 * @package block-developer-examples
 */

$flow_id   = $attributes['flow_id'] ?? false;
ob_start();
?>
    <div <?php echo get_block_wrapper_attributes(); ?>>
        <div data-flow_id="<?php echo esc_attr( $flow_id ); ?>"
             data-flow_data="<?php echo esc_attr( json_encode([]) ); ?>"></div>
    </div>
<?php
$block_content = ob_get_clean();

echo wp_kses_post( $block_content );
