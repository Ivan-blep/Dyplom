<h6><?php _e( 'Share:', 'default' ); ?></h6>
<ul class="share-links flex-container">
	<li class="share-links__item">
		<a href="<?php echo get_share_link_url( get_the_ID(), 'facebook' ); ?>"
		   class="share-links__link js-share-link" target="_blank"><span class="fab fa-facebook-f"></span></a>
	</li>
	<li class="share-links__item">
		<a href="<?php echo get_share_link_url( get_the_ID(), 'twitter' ); ?>"
		   class="share-links__link js-share-link" target="_blank"><span class="fab fa-twitter"></span></a>
	</li>
	<li class="share-links__item">
		<a href="<?php echo get_share_link_url( get_the_ID(), 'linkedin' ); ?>"
		   class="share-links__link js-share-link" target="_blank"><span class="fab fa-linkedin-in"></span></a>
	</li>
	<li class="share-links__item">
		<a href="<?php echo get_share_link_url( get_the_ID(), 'tumblr' ); ?>"
		   class="share-links__link js-share-link" target="_blank"><span class="fab fa-tumblr"></span></a>
	</li>
	<li class="share-links__item">
		<a href="<?php echo get_share_link_url( get_the_ID(), 'reddit' ); ?>"
		   class="share-links__link js-share-link" target="_blank"><span class="fab fa-reddit"></span></a>
	</li>
	<?php if ( has_post_thumbnail( get_the_ID() ) ): ?>
		<li class="share-links__item">
			<a href="<?php echo get_share_link_url( get_the_ID(), 'pinterest' ); ?>"
			   class="share-links__link js-share-link" target="_blank"><span class="fab fa-pinterest-p"></span></a>
		</li>
	<?php endif; ?>
</ul>