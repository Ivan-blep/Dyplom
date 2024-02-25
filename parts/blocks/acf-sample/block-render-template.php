<?php
/**
 * @var array $block The block settings and attributes.
 * @var string $content The block inner HTML (empty).
 * @var bool $is_preview True during AJAX preview.
 * @var int|string $post_id The post ID this block is saved to.
 */

// TODO Change block CSS class `acf-custom-block` to yours for further styling
//

?>
<section <?php echo starter_block_attributes( 'acf-custom-block', $block ); ?>>
	<?php if ( $block_title = get_field( 'block_title' ) ): ?>
		<h2><?php echo $block_title; ?></h2>
	<?php endif; ?>
</section>