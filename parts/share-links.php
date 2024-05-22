<div class="share-box">
	<h6 class="share-box__title"><?php _e( 'Share:' ); ?></h6>
	<ul class="share-box__links share-links">
		<?php
		$share_links = [
			'facebook'  => 'fab fa-facebook-f',
			'twitter'   => 'fab fa-twitter',
			'linkedin'  => 'fab fa-linkedin-in',
			'tumblr'    => 'fab fa-tumblr',
			'reddit'    => 'fab fa-reddit',
			'pinterest' => 'fab fa-pinterest-p',
			'email'     => 'far fa-envelope',
		];

		foreach ( $share_links as $network => $icon ):
			// Skip pinterest if post does not have thumbnail
			if ( $network == 'pinterest' && ! has_post_thumbnail( get_the_ID() ) ) {
				continue;
			} 
			?>
			<li class="share-links__item">
				<a href="<?php echo get_share_link_url( get_the_ID(), $network ); ?>"
               class="share-links__link <?php echo $network !== 'email' ? 'js-share-link' : ''; ?>" target="_blank">
               <span class="<?php echo $icon; ?>"></span></a>
			</li>
		<?php endforeach; ?>
	</ul>
</div>