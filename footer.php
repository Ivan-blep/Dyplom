<?php
/**
 * Footer
 */
?>

<!-- BEGIN of footer -->
<footer class="footer">
	<div class="grid-container">
		<div class="grid-x">
			<div class="footer__logo-container">
				<div class="footer__logo">
					<?php if ( $footer_logo = get_field( 'footer_logo', 'options' ) ):
						$logo_image = wp_get_attachment_image( $footer_logo['id'], 'medium', false, [
							'class'    => 'custom-logo',
							'itemprop' => 'siteLogo',
							'alt'      => get_bloginfo( 'name' ),
						] );
						echo sprintf( '<a href="%1$s" class="custom-logo-link" rel="home" title="%2$s" itemscope>%3$s</a>', esc_url( home_url( '/' ) ), get_bloginfo( 'name' ), $logo_image );
					else:
						show_custom_logo();
					endif; ?>
				</div>
                <?php if($button = get_field('button', 'options')): echo acf_link($button,'button'); endif;?>
			</div>
            <div class="footer__right-content">
            <div class="footer__contact">
                <p class="footer__contact--title"><?php _e('Questions?')?></p>
                <?php if($phone = get_field('phone', 'options')):?>
                    <p class="footer__contact--phone">
                        <?php _e('Phone');?>
                        <a href="tel:<?php echo sanitize_number( $phone ); ?>"><?php echo $phone; ?></a>
                    </p>
                <?php endif;?>
                <?php if ( $email = get_field( 'email', 'options' ) ): ?>
                    <p class="footer__contact--email"><?php _e('Email');?> <a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a></p>
                <?php endif; ?>
                <div class="footer__sp">
                    <p class="footer__contact--social"><?php _e('Social');?></p>
                    <?php get_template_part( 'parts/socials' ); // Social profiles ?>
                </div>
            </div>
            <div class="footer-menu__container">
                <?php
                if ( has_nav_menu( 'footer-menu' ) ) {
                    wp_nav_menu( array( 'theme_location' => 'footer-menu', 'menu_class' => 'footer-menu menu', 'depth' => 1 ) );
                }
                ?>
            </div>
		</div>
        </div>
	</div>
</footer>
<!-- END of footer -->

<?php wp_footer(); ?>

<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>-->
</body>
</html>