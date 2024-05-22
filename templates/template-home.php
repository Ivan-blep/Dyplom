<?php
/**
 * Template Name: Home Page
 */
get_header(); ?>

    <!--HOME PAGE SLIDER-->
<?php home_slider_template(); ?>
    <!--END of HOME PAGE SLIDER-->
    <div class="home-hero-image">
        <?php if ($hero_image = get_field('hero_image')): echo wp_get_attachment_image($hero_image['id'], 'full_hd', false, array('class' => '')); endif; ?>
    </div>    <!-- BEGIN of main content -->

<?php
$gallery_images = get_field('gallery');
if ($gallery_images): ?>
    <script>
        var galleryArray = [];
        <?php foreach ($gallery_images as $image): ?>
        galleryArray.push({
            'url': '<?php echo $image['image']['url']; ?>',
        });
        <?php endforeach; ?>
    </script>
<?php endif; ?>

    <section class="bringing-comfort bringing-comfort__large">
        <div class="bringing-comfort__left-image">
        </div>
        <div class="bringing-comfort__right-content">
            <div class="bringing-comfort__right-content--container-images">
                <div class="bringing-comfort__center-image">
                </div>
                <div class="bringing-comfort__right-image">
                </div>
            </div>
            <div class="bringing-comfort__right-content--container-text">
                <?php echo get_field('bringing_comfort_content')??''?>
            </div>
        </div>
    </section>

    <section class="bringing-comfort bringing-comfort__medium">
        <div class="bringing-comfort__right-content--container-images">
            <?php
            $gallery_images = get_field('gallery');
            if ($gallery_images): ?>
                    <?php foreach ($gallery_images as $image): ?>
                        <div class="bringing-comfort__right-content--container-images__item">
                            <?php echo ( $image['image']) ? wp_get_attachment_image($image['image']['id'], 'large', false, array('class' => 'overlay')):''?>
                        </div>
                    <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="bringing-comfort__right-content">
            <div class="bringing-comfort__right-content--container-text">
                <?php echo get_field('bringing_comfort_content')??''?>
            </div>
        </div>
    </section>

    <section class="broadmoor-family">
        <div class="broadmoor-family__bg">
            <img class="" src="<?php echo get_template_directory_uri()?>/assets/images/bg1.png" alt="">
        </div>
        <div class="grid-container">
            <?php if ($broadmoor_family_title = get_field('broadmoor_family_title')): ?>
                <div class="broadmoor-family__title"><h5 class="text-center"><?php echo $broadmoor_family_title ?></h5>
                </div>
            <?php endif; ?>
            <?php if ($content = get_field('content')): ?>
                <div class="grid-x">
                    <?php foreach ($content as $item): ?>
                        <div class="sell large-6 broadmoor-family__item">
                            <?php if ($item['icon']): ?>
                                <div class="broadmoor-family__item--icon">
                                    <?php echo wp_get_attachment_image($item['icon']['id'], 'full_hd', false, array('class' => '')); ?>
                                    <?php if ($hover_icon = get_field('hover_icon', 'options')): echo wp_get_attachment_image($hover_icon['id'], 'full_hd', false, array('class' => 'broadmoor-family__item--icon__hover')); endif; ?>
                                </div>
                            <?php endif; ?>
                            <?php if ($item['image']): ?>
                                <div class="broadmoor-family__item--image">
                                    <?php echo wp_get_attachment_image($item['image']['id'], 'full_hd', false, array('class' => '')); ?>
                                </div>
                            <?php endif; ?>
                            <?php if ($item['content']): ?>
                                <div class="broadmoor-family__item--content">
                                    <?php echo $item['content'] ?>
                                </div>
                            <?php endif; ?>
                            <?php echo ($item['button']) ? acf_link($item['button'], 'button') : '' ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <section class="meet">
        <?php if ($meet_image = get_field('meet_image')): ?>
            <div class="meet__image animated-element slide-left">
                <?php echo wp_get_attachment_image($meet_image['id'], 'full_hd', false, array('class' => '')); ?>
            </div>
        <?php endif; ?>
        <div class="meet__content animated-element slide-right">
            <div class="meet__content--container">
                <?php echo get_field('meet_content') ?? '' ?>
            </div>
        </div>
    </section>

    <div class="video-container <?php if (strpos(get_field('link_video'), 'youtube.com') !== false && get_field('autoplay')) echo 'z-1'; ?>">
        <?php if ((get_field('link_video')) && (get_field('video_source') == 'youtube')) :?>
        <?php if (strpos(get_field('link_video'), 'youtube.com') !== false) : ?>
            <?php preg_match('/[\\?\\&]v=([^\\?\\&]+)/', get_field('link_video'), $matches); ?>
            <?php $youtube_video_id = $matches[1]; ?>
            <div id="playerContainer" data-autoplay="<?php echo (get_field('autoplay') ? 'autoplay-on' : 'autoplay-off'); ?>" data-youtube-id="<?php echo $youtube_video_id; ?>"></div>
        <?php if (!get_field('autoplay')) : ?>
            <button class="play-button opacity-0"></button>
        <?php endif;?>
        <?php endif; ?>
        <?php endif;?>
        <?php if(($video = get_field('video'))  && (get_field('video_source') == 'media_library')):?>
            <video id="video" preload="metadata" class="<?php echo (get_field('overlay_image')) ? 'opacity' : ''; ?>"<?php if (get_field('autoplay')) : ?>autoplay muted style="opacity: 1 !important;" <?php endif; ?> loop>
                <source src="<?php echo $video ?? ''; ?>" type="video/mp4">
            </video>
            <?php if (!get_field('autoplay')) : ?>
            <button class="play-button"></button>
            <?php echo ($overlay = get_field('overlay_image')) ? wp_get_attachment_image($overlay['id'], 'full_hd', false, array('class' => 'overlay')) : ''; ?>
        <?php endif; ?>
        <?php endif; ?>

    </div>







    <section class="testimonials grid-container">
        <h5 class="testimonials__title"><?php echo get_field('tesimonials_title') ?? '' ?></h5>
        <?php
        $categories = get_terms(array(
            'taxonomy' => 'testimonials_category',
            'hide_empty' => false,
            'order' => 'DESC',
        )); ?>

        <div class="tab-wrapper">
            <ul class="vertical tabs"  data-tabs id="collapsing-tabs">
                <?php $n = 0;
                foreach ($categories as $category) : ?>
                    <li class="tabs-title <?php echo ($n == 0) ? ' is-active' : '';
                    $n++ ?>"><a href="#<?php echo $category->slug; ?>"><?php echo $category->name; ?></a></li>
                <?php endforeach; ?>
            </ul>
            <div class="tab-content" data-tabs-content="collapsing-tabs">
                <?php $i = 0;
                foreach ($categories as $category) : ?>
                    <div id="<?php echo $category->slug; ?>"
                         class="tabs-panel <?php echo ($i == 0) ? ' is-active' : ''; ?>">
                        <div class="slider-<?php echo $category->slug;
                        echo ($i == 0) ? ' is-active-slider' : '';
                        $i++ ?>">
                            <?php
                            $args = array(
                                'post_type' => 'testimonials',
                                'posts_per_page' => -1,
                                'tax_query' => array(
                                    array(
                                        'taxonomy' => 'testimonials_category',
                                        'field' => 'slug',
                                        'terms' => $category->slug,
                                    ),
                                ),
                            );

                            $query = new WP_Query($args);
                            $k=1;
                            if ($query->have_posts()) :
                                while ($query->have_posts()) : $query->the_post(); ?>
                                    <div class="tab-slid">
                                        <?php if($k % 3 == 1){?>
                                            <img class="tab-slid__bg" src="<?php echo get_template_directory_uri()?>/assets/images/bg-tab1.svg" alt="">
                                        <?php }
                                        elseif ($k % 3 == 2){?>
                                            <img class="tab-slid__bg" src="<?php echo get_template_directory_uri()?>/assets/images/bg-tab2.svg" alt="">
                                        <?php }
                                        else{?>
                                        <img class="tab-slid__bg" src="<?php echo get_template_directory_uri()?>/assets/images/bg-tab3.svg" alt="">
                                        <?php }?>
                                        <h5 class="beloved"><?php echo get_the_title(); ?></h5>
                                        <?php $stars = get_field('stars');
                                        $stars_number = intval($stars);
                                        $i = 1;
                                        while ($i <= $stars_number) {
                                            echo '<i class="fa-solid fa-star"></i>';
                                            $i++;
                                            $k++;
                                        }
                                        ?>
                                        <div class="testimonials-content">
                                            <?php echo get_the_content() ?? '' ?>
                                        </div>
                                    </div>

                                <?php endwhile;
                                wp_reset_postdata();
                            endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </section>


    <!-- END of main content -->


<?php get_footer(); ?>