<?php
/**
 * Template Name: About Page
 */
get_header(); ?>
<?php if ($about_hero_image = get_field('about_hero_image')): ?>
    <div class="about-hero-image">
        <h1>
            <?php the_title()?>
        </h1>
        <?php echo wp_get_attachment_image($about_hero_image['id'], 'full_hd', false, array('class' => '')); ?>
    </div>
<?php endif; ?>
    <section class="about-hero animated-element slide-left">
        <div class="grid-container">
            <div class="about-hero__container">
                <h3 class="about-hero__title"><?php the_title() ?></h3>
                <?php echo get_the_content() ?? '' ?>
            </div>
        </div>
    </section>
    <section class="your-team">
        <?php if ($team_title = get_field('team_title')): ?>
            <div class="your-team__title">
                <div class="grid-container">
                    <?php echo $team_title ?>

                </div>
            </div>
        <?php endif; ?>
        <div class="grid-container your-team__content">
            <div class="your-team__banner">
                <div class="your-team__banner--image animated-element slide-left">
                    <?php if ($banner_image = get_field('banner_image')): echo wp_get_attachment_image($banner_image['id'], 'full_hd', false, array('class' => '')); endif; ?>
                </div>
                <div class="your-team__banner--contact animated-element slide-left">
                    <?php if ($name = get_field('name')): ?>
                        <h4 class="your-team__banner--contact__name"> <?php echo $name ?></h4>
                    <?php endif; ?>

                    <?php if ($job_title = get_field('job_title')): ?>
                        <p class="your-team__banner--contact__job-title"> <?php echo $job_title ?></p>
                    <?php endif; ?>
                </div>
                <div class="your-team__banner--content animated-element slide-right">
                    <?php echo get_field('banner_content') ?? '' ?>
                </div>
            </div>
        </div>
        <div class="grid-container your-team__item-content">
            <?php
            $args = array(
                'post_type' => 'team',
                'posts_per_page' => -1,
                'order' => 'ASC',
            );

            $team_query = new WP_Query($args);

            if ($team_query->have_posts()) :
                while ($team_query->have_posts()) : $team_query->the_post();
                    $post_id = get_the_ID();
                    $featured_image_id = get_post_thumbnail_id();
                    if ($featured_image_id) :?>
                        <div class="your-team__item animated-element slide-up">
                            <i class="fa-solid fa-plus" data-id="<?php echo $post_id; ?>"></i>
                            <?php echo wp_get_attachment_image($featured_image_id, 'full_hd', false, array('class' => '')); ?>
                        </div>
                    <?php endif;
                endwhile;
                wp_reset_postdata();
            endif;
            ?>

            <div id="teamPopup" class="grid-container"></div>

        </div>
    </section>
    <section class="top-rated">
        <div class="top-rated__bg">
            <img class="" src="<?php echo get_template_directory_uri()?>/assets/images/top-bg.svg" alt="">
        </div>
        <div class="top-rated__image animated-element slide-left">
            <?php if ($top_rated_image = get_field('top_rated_image')) echo wp_get_attachment_image($top_rated_image['id'], 'full_hd', false, array('class' => '')); ?>
            <div class="top-rated--icon">
                <?php if ($top_rated_icon = get_field('top_rated_icon')): echo wp_get_attachment_image($top_rated_icon['id'], 'full_hd', false, array('class' => '')); endif; ?>
                <?php if ($hover_icon = get_field('hover_icon', 'options')): echo wp_get_attachment_image($hover_icon['id'], 'full_hd', false, array('class' => 'top-rated--icon__hover')); endif; ?>
            </div>
        </div>
        <div class="top-rated__content animated-element slide-right">
            <?php echo get_field('top_rated_content') ?? '' ?>
        </div>
    </section>
    <section class="difference">
        <img class="difference__bg animated-element slide-left" src="<?php echo get_template_directory_uri()?>/assets/images/difference-bg.svg" alt="">
        <img class="difference__logo animated-element slide-right" src="<?php echo get_template_directory_uri()?>/assets/images/BFD_Logo_Vertical_FullWhite.svg" alt="">
        <div class="grid-container">
            <div class="difference__title"><?php echo get_field('difference_title') ?? '' ?></div>
            <div class="dropdown-container">
                <?php if ($dropdown = get_field('dropdown')):
                    foreach ($dropdown as $item):
                        ?>

                        <div class="dropdown">
                            <div class="dropdown-toggle">
                                <div class="dropdown-toggle__icon">
                                    <div>
                                        <?php echo ($item['icon']) ? wp_get_attachment_image($item['icon']['id'], 'full_hd', false, array('class' => '')) : '' ?>
                                    </div>
                                </div>
                            </div>
                            <ul class="dropdown-menu">
                                <li>
                                    <div class="dropdown-menu__icon">
                                        <div>
                                            <?php echo ($item['active_icon']) ? wp_get_attachment_image($item['active_icon']['id'], 'full_hd', false, array('class' => '')) : '' ?>
                                        </div>
                                    </div>
                                    <div class="dropdown-menu__content">
                                        <?php echo $item['content'] ?? '' ?>
                                    </div>
                                </li>

                            </ul>
                        </div>
                    <?php endforeach; endif; ?>
            </div>
        </div>
    </section>

    <section class="we-love">
        <div class="grid-container">
            <div class="we-love__content">
                <div class="we-love__content--image animated-element slide-left">
                    <?php if ($we_love_image = get_field('we_love_image')): echo wp_get_attachment_image($we_love_image['id'], 'full_hd', false, array('class' => '')); endif; ?>
                </div>
                <div class="we-love__content--text animated-element slide-right">
                    <?php echo get_field('we_love_content') ?? '' ?>
                </div>
            </div>
            <?php if ($we_love_gallery = get_field('we_love_gallery')): ?>
                <div class="we-love__gallery">
                    <?php foreach ($we_love_gallery as $gallery_item): ?>
                        <div class="we-love__gallery--item">
                            <?php echo wp_get_attachment_image($gallery_item['id'], 'full_hd', false, array('class' => '')); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
<?php get_footer(); ?>