<?php
/**
 * Template Name: Contact Page
 */

get_header(); ?>
	
	<main class="main-content">
		<section class="contact">
			<?php if ( have_posts() ): ?>
				<?php while ( have_posts() ): the_post(); ?>
					<article id="<?php the_ID(); ?>" <?php post_class(); ?>>
						<div class="grid-container">
							<div class="grid-x grid-margin-x">
								<div class="cell medium-6">
									<h1 class="page-title"><?php the_title(); ?></h1>
									<?php if ( get_the_content() ): ?>
										<div class="contact__content gb-content">
											<?php the_content(); ?>
										</div>
									<?php endif; ?>
									
									<div class="contact__links">
										<?php if ( $address = get_field( 'address', 'option' ) ): ?>
											<address class="contact-link contact-link--address">
												<a href="<?php echo get_address_url( $address ); ?>"><?php echo $address; ?></a>
											</address>
										<?php endif; ?>
										<?php if ( $email = get_field( 'email', 'options' ) ): ?>
											<p class="contact-link contact-link--email"><a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a></p>
										<?php endif; ?>
										<?php if ( $phone = get_field( 'phone', 'options' ) ): ?>
											<p class="contact-link contact-link--phone"><a href="tel:<?php echo sanitize_number( $phone ); ?>"><?php echo $phone; ?></a>
											</p>
										<?php endif; ?>
									</div>
								</div>
								<?php if ( class_exists( 'GFAPI' ) && ( $contact_form = get_field( 'contact_form' ) ) && is_array( $contact_form ) ): ?>
									<div class="cell medium-6">
										<div class="contact__form">
											<?php echo do_shortcode( "[gravityform id='{$contact_form['id']}' title='false' description='false' ajax='true']" ); ?>
										</div>
									</div>
								<?php endif; ?>
								<?php
								$map_type      = get_field( 'map_type', 'options' );
								$map_field_key = $map_type == 'google' ? 'location' : ( $map_type == 'iframe' ? 'map_iframe' : 'map_image' );
								$location      = get_field( $map_field_key, 'options' );

								if ( $location ): ?>
									<div class="cell contact__map-wrap">
										<?php if ( $map_type == 'google' ): ?>
											<div class="acf-map contact__map">
												<div class="marker" data-lat="<?php echo $location['lat']; ?>" data-lng="<?php echo $location['lng']; ?>"
												     data-marker-icon="<?php echo IMAGE_ASSETS . 'map-marker.png'; ?>"><?php echo '<p>' . $location['address'] . '</p>'; ?></div>
											</div>
										<?php elseif ( $map_type == 'iframe' ): ?>
											<div class="contact__map">
												<?php echo $location; ?>
											</div>
										<?php else: ?>
											<div class="contact__map">
												<?php echo wp_get_attachment_image( $location, '1536x1536', false, array(
													'class' => 'contact__map-img',
													'alt'   => get_field( 'address', 'options' ) ?: '',
												) ); ?>
											</div>
										<?php endif; ?>
									</div>
								<?php endif; ?>
							
							</div>
						</div>
					</article>
				<?php endwhile; ?>
			<?php endif; ?>
		</section>
	</main>

<?php get_footer(); ?>