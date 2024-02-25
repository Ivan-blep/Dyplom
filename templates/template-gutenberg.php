<?php
/**
 * Template Name: Gutenberg Page
 */
get_header(); ?>
	
	<main class="main-content">
		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<article <?php post_class('entry gb-content gb-content--full'); ?>>
					<h1 class="page-title entry__title"><?php the_title(); ?></h1>
					<?php the_content( '', true ); ?>
				</article>
			<?php endwhile; ?>
		<?php endif; ?>
	</main>

<?php get_footer(); ?>