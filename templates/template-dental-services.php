<?php
/**
 * Template Name: Dental Services Page
 */
get_header(); ?>
<?php if ($about_hero_image = get_field('services_hero_image')): ?>
    <div class="services-hero-image">
        <div class="services-hero-image__container">
            <?php echo wp_get_attachment_image($about_hero_image['id'], 'full_hd', false, array('class' => '')); ?>
        </div>
    </div>
<?php endif; ?>
    <section class="services-hero">

        <div class="services-hero__title">
            <div class="grid-container">
                <h1 class="animated-element slide-left">
                    <?php
                    $title = the_title('', '', false);
                    $words = explode(' ', $title);
                    $lastWord = array_pop($words);
                    foreach ($words as $word) {
                        echo $word . ' ';
                    }
                    echo '<span style="color: #68BC95FF;">' . $lastWord . '</span>';
                    ?>
                </h1>
            </div>
        </div>
        <div class="grid-container">
            <div class="services-hero__content animated-element slide-left">
                <?php echo get_the_content() ?? '' ?>
            </div>
        </div>
    </section>
    <section class="general-dental">
        <div class="general-dental__image animated-element slide-left">
            <?php echo ($general_dentistry_image = get_field('general_dentistry_image')) ? wp_get_attachment_image($general_dentistry_image['id'], 'full_hd', false, array('class' => '')) : '' ?>
        </div>
        <div class="general-dental__content animated-element slide-right">
            <?php echo get_field('general_dentistry_content') ?? '' ?>
            <ul class="general-dental__content--list">
                <?php $args = array(
                    'post_type' => 'general-dentistry',
                    'posts_per_page' => -1,
                );
                $general = new WP_Query($args);
                if ($general->have_posts()) :
                    while ($general->have_posts()) :
                        $general->the_post();
                        $post_slug = get_post_field('post_name', get_the_ID());
                        if ($title = get_the_title()) :
                            $permalink = get_permalink();
                            ?>
                            <li><a href="<?php echo get_post_type_archive_link('general-dentistry') . '#' . $post_slug; ?>"><?php echo $title ?></a></li>
                        <?php
                        endif;
                    endwhile;
                    wp_reset_postdata();
                endif;
                ?>
            </ul>
            <?php echo ($button = get_field('general_dentistry_button')) ? acf_link($button, 'button') : '' ?>
        </div>
    </section>

    <section class="cosmetic-dentistry">
        <div class="cosmetic-dentistry__image animated-element slide-right">
            <?php echo ($general_dentistry_image = get_field('cosmetic_dentistry_image')) ? wp_get_attachment_image($general_dentistry_image['id'], 'full_hd', false, array('class' => '')) : '' ?>
        </div>
        <div class="cosmetic-dentistry__content animated-element slide-left">
            <?php echo get_field('cosmetic_dentistry_content') ?? '' ?>
            <ul class="cosmetic-dentistry__content--list">
                <?php
                $args = array(
                    'post_type' => 'cosmetic-dentistry',
                    'posts_per_page' => -1,
                );
                $general = new WP_Query($args);
                if ($general->have_posts()) :
                    while ($general->have_posts()) :
                        $general->the_post();
                        $post_slug = get_post_field('post_name', get_the_ID());
                        if ($title = get_the_title()) :
                            $permalink = get_permalink();
                            ?>
                            <li><a href="<?php echo get_post_type_archive_link('cosmetic-dentistry') . '#' . $post_slug; ?>"><?php echo $title ?></a></li>
                        <?php
                        endif;
                    endwhile;
                    wp_reset_postdata();
                endif;
                ?>
            </ul>
            <?php echo ($button = get_field('cosmetic_dentistry_button')) ? acf_link($button, 'button') : '' ?>
        </div>
    </section>
    <section class="preventative-dentistry">
        <div class="preventative-dentistry__image animated-element slide-left">
            <?php echo ($general_dentistry_image = get_field('preventative__dentistry_image')) ? wp_get_attachment_image($general_dentistry_image['id'], 'full_hd', false, array('class' => '')) : '' ?>
        </div>
        <div class="preventative-dentistry__content animated-element slide-right">
            <?php echo get_field('preventative__dentistry_content') ?? '' ?>
            <ul class="preventative-dentistry__content--list">
                <?php $args = array(
                    'post_type' => 'preventative-dentist',
                    'posts_per_page' => -1,
                );
                $general = new WP_Query($args);
                if ($general->have_posts()) :
                    while ($general->have_posts()) :
                        $general->the_post();
                        $post_slug = get_post_field('post_name', get_the_ID());
                        if ($title = get_the_title()) :
                            $permalink = get_permalink();
                            ?>
                            <li><a href="<?php echo get_post_type_archive_link('preventative-dentist') . '#' . $post_slug; ?>"><?php echo $title ?></a></li>
                        <?php
                        endif;
                    endwhile;
                    wp_reset_postdata();
                endif;
                ?>
            </ul>
            <?php echo ($button = get_field('preventative__dentistry_button')) ? acf_link($button, 'button') : '' ?>
        </div>
    </section>

<?php get_footer(); ?>