<?php if ( have_rows( 'socials', 'options' ) ): ?>
	<ul class="stay-tuned">
		<?php while ( have_rows( 'socials', 'options' ) ): the_row(); ?>
		<?php $social_network = get_sub_field('social_network'); ?>
			<li class="stay-tuned__item">
                <?php if($social_network['label'] == 'Facebook'):?>
                <a class="stay-tuned__link facebook"
                   href="<?php the_sub_field( 'social_profile' ); ?>"
                   target="_blank"
                   rel="noopener"><svg xmlns="http://www.w3.org/2000/svg" width="1.5rem" height="1.5rem" viewBox="0 0 22 21">
                        <g id="iconoir:facebook" transform="translate(0.083 -0.25)">
                            <rect id="iconoir:facebook-2" data-name="iconoir:facebook" width="22" height="21" transform="translate(-0.083 0.25)" fill="none"/>
                            <path id="Vector" d="M10.007,3.639H7.278a.91.91,0,0,0-.91.91V7.278h3.639L9.1,10.917H6.368v7.278H2.729V10.917H0V7.278H2.729V4.549A4.549,4.549,0,0,1,7.278,0h2.729Z" transform="translate(5.153 1.514)" fill="none" stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                        </g>
                    </svg>

                </a>
                <?php else:?>
				<a class="stay-tuned__link "
				   href="<?php the_sub_field( 'social_profile' ); ?>"
				   target="_blank"
				   aria-label="<?php echo $social_network['label']; ?>"
				   rel="noopener"><span aria-hidden="true" class="fab fa-<?php echo $social_network['value']; ?>"></span>
				</a>
                <?php endif;?>
			</li>
		<?php endwhile; ?>
	</ul>
<?php endif; ?>
