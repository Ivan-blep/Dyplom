<?php
/**
 * Template Name: Contact Page
 */

get_header(); ?>
<?php if (have_posts()): ?>
    <?php while (have_posts()): the_post(); ?>
        <div class="contact-hero-image" <?php bg(get_field('hero_image')) ?>></div>
        <section class="contact-hero">
            <div class="grid-container">
                <div class="contact-hero__address  animated-element slide-up">
                    <?php if ($address = get_field('address', 'option')): ?>
                    <div class="contact-hero__address--title">
                        <h5><?php _e('Our Office') ?></h5>
                    </div>
                    <div class="contact-hero__address--content">
                        <address class="contact-link contact-link--address">
                            <img src="<?php echo get_template_directory_uri() ?>/assets/images/Icon-akar-location.svg"
                                 alt="">
                            <a href="<?php echo get_address_url($address); ?>" target="_blank"><?php echo $address; ?></a>
                        </address>
                    </div>
                </div>
                <?php endif; ?>
                <?php if ($office_hours = get_field('office_hours', 'options')): ?>
                    <div class="contact-hero__office-hours  animated-element slide-up">
                        <div class="contact-hero__office-hours--title">
                            <h5><?php _e('Office Hours') ?></h5>
                        </div>
                        <div class="contact-hero__office-hours--content">
                            <?php foreach ($office_hours as $item): ?>
                                <div class="contact-hero__office-hours--content__item">
                                    <div class="contact-hero__office-hours--content__item--day">
                                        <?php echo $item['day'] ?>
                                    </div>
                                    <div class="contact-hero__office-hours--content__item--time">
                                        <?php echo $item['work_time'] ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif;
                $email = get_field('email', 'options');
                $phone = get_field('phone', 'options')
                ?>
                <?php if (($email) || ($phone)): ?>
                    <div class="contact-hero__contact  animated-element slide-up">
                        <div class="contact-hero__contact--title">
                            <h5><?php _e('Contact') ?></h5>
                        </div>
                        <div class="contact-hero__contact--content">
                            <?php if ($phone): ?>
                                <p class="contact-link contact-link--phone"><a
                                            href="tel:<?php echo sanitize_number($phone); ?>"><?php echo $phone; ?></a>
                                </p>
                            <?php endif; ?>
                            <?php if ($email): ?>
                                <p class="contact-link contact-link--email">
                                    <img src="<?php echo get_template_directory_uri() ?>/assets/images/email.svg"
                                         alt="">
                                    <a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a></p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </section>
        <section class="contact-content">
            <?php
            $map_type = get_field('map_type', 'options');
            $map_field_key = $map_type == 'google' ? 'location' : ($map_type == 'iframe' ? 'map_iframe' : 'map_image');
            $location = get_field($map_field_key, 'options');

            if ($location): ?>
                <div class="cell contact__map-wrap  animated-element slide-left">
                    <?php if ($map_type == 'google'): ?>
                        <div class="acf-map contact__map">
                            <div class="marker" data-lat="<?php echo $location['lat']; ?>"
                                 data-lng="<?php echo $location['lng']; ?>"
                                 data-marker-icon="<?php echo IMAGE_ASSETS . 'map-marker.png'; ?>"><?php echo '<p>' . $location['address'] . '</p>'; ?></div>
                        </div>
                    <?php elseif ($map_type == 'iframe'): ?>
                        <div class="contact__map">
                            <?php echo $location; ?>
                        </div>
                    <?php else: ?>
                        <div class="contact__map">
                            <?php echo wp_get_attachment_image($location, '1536x1536', false, array(
                                'class' => 'contact__map-img',
                                'alt' => get_field('address', 'options') ?: '',
                            )); ?>
                        </div>
                    <?php endif; ?>

                </div>
            <?php endif; ?>
            <?php if (class_exists('GFAPI') && ($contact_form = get_field('contact_form')) && is_array($contact_form)): ?>
                <div class="contact-content__form  animated-element slide-right">
                    <?php echo do_shortcode("[gravityform id='{$contact_form['id']}' title='false' description='false' ajax='true']"); ?>
                </div>
            <?php endif; ?>
        </section>
    <?php endwhile; endif; ?>


<?php get_footer(); ?>