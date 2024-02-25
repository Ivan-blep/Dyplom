<?php
/**
 * @var array $block The block settings and attributes.
 * @var string $content The block inner HTML (empty).
 * @var bool $is_preview True during AJAX preview.
 * @var int|string $post_id The post ID this block is saved to.
 */

// TODO Change block CSS class `acf-custom-block` to yours for further styling 
?>
<section class="<?php echo starter_section_class( 'hero-slider-box', $block ); ?>">
	<?php home_slider_template(); ?>
</section>