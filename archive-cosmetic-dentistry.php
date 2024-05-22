<?php
/**
 * Archive
 *
 * Standard loop for the archive page
 */
get_header(); ?>

    <section class="services-archive-hero">
        <div class="grid-container">
            <div class="services-archive-hero__content-container">
                <div class="services-archive-hero__title animated-element slide-left cosmetic-title">
                    <?php echo get_field('cosmetic_dentistry_title', 'options') ?? '' ?>
                </div>
                <div class="services-archive-hero__content animated-element slide-right cosmetic-content">
                    <?php echo get_field('cosmetic_dentistry_content', 'options') ?? '' ?>
                </div>
            </div>
            <ul class="services-archive-hero__list">
                <?php if (have_posts()):
                    while (have_posts()):
                        the_post();
                        if ($title = get_the_title()):
                            ?>
                            <li>
                                <a href="#<?php echo get_post_field('post_name', get_the_ID()); ?>"><?php echo $title ?></a>
                            </li>
                        <?php
                        endif;
                    endwhile;
                endif; ?>
            </ul>
        </div>
    </section>
    <section class="services-archive-posts">
        <?php
        $counter = 0;
        if (have_posts()):
            while (have_posts()):
                the_post();
                $counter++;
                $class = '';
                if ($counter % 3 == 1) {
                    $class = 'green';
                } elseif ($counter % 3 == 2) {
                    $class = 'light-green';
                } else {
                    $class = 'dark-green';
                }
                ?>
                <div id="<?php echo get_post_field('post_name', get_the_ID()); ?>"
                     class="services-archive-posts__item <?php echo $class; ?>">

                    <div class="services-archive-posts__item--image animated-element slide-left">
                        <?php echo ($featured_image_id = get_post_thumbnail_id()) ? wp_get_attachment_image($featured_image_id, 'full_hd', false, array('class' => '')) : '' ?>
                    </div>
                    <div class="services-archive-posts__item--content animated-element slide-right">
                        <div class="plus" data-id="<?php echo get_post_field('post_name', get_the_ID()); ?>">
                            <i class="fa-solid fa-plus"></i>
                            <i class="fa-solid fa-minus"></i>
                        </div>
                        <div>
                            <h4 class="services-archive-posts__item--content__title">
                                <?php echo get_the_title() ?? '' ?>
                            </h4>
                            <a href="#" data-id="<?php echo get_post_field('post_name', get_the_ID()); ?>"
                               class="learn-more"><?php _e('Learn More  ') ?></a>
                            <div class="services-archive-posts__item--content__text">
                                <?php echo get_the_content() ?? '' ?>
                            </div>
                        </div>
                        <div class="services-archive-posts__item--content__buttons">
                            <?php echo ($first_button = get_field('first_button')) ? acf_link($first_button, 'button') : '' ?>
                            <?php echo ($second_button = get_field('second_button')) ? acf_link($second_button, 'button') : '' ?>
                        </div>
                    </div>
                    <div class="template <?php echo get_post_field('post_name', get_the_ID()); ?>">
                        <?php if (get_field('Template') == 'Template 1'): ?>
                        <div class="template-1">
                            <div class="grid-container">
                                <div class="template-1__left">
                                    <div class="template-1__left--content">
                                        <?php echo get_field('1_content') ?? '' ?>
                                    </div>
                                    <?php if ($sub_content = get_field('1_sub_content')): ?>
                                        <div class="template-1__left--sub-content">
                                            <?php echo $sub_content ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="template-1__right<?php echo (get_field('one_column'))?' one-column':''?>">
                                    <img class="template-1__right__bg" src="<?php echo get_template_directory_uri()?>/assets/images/difference-bg.svg" alt="">
                                    <?php if ($list = get_field('1_list')):
                                        foreach ($list as $item): ?>
                                            <div class="template-1__right--list-item <?php echo ($item['full_width'])?'full':'' ?>">
                                                <div class="template-1__right--list-item__icon">
                                                    <?php echo ($icon = $item['icon']) ? wp_get_attachment_image($icon['id'], 'full_hd', false, array('class' => '')) : '' ?>
                                                </div>
                                                <div class="template-1__right--list-item__description">
                                                    <?php echo $item['description'] ?>
                                                </div>
                                            </div>
                                        <?php endforeach; endif; ?>
                                </div>
                            </div>
                        </div>
                            <?php endif; ?>
                            <?php if (get_field('Template') == 'Template 3'): ?>
                            <div class="template-3">
                                <div class="grid-container">
                                    <div class="template-3__left">
                                        <img class="template-3__left__bg" src="<?php echo get_template_directory_uri()?>/assets/images/difference-bg.svg" alt="">
                                        <div class="template-3__left--content">
                                            <?php echo get_field('3_content') ?? '' ?>
                                        </div>
                                        <?php if ($sub_content = get_field('3_sub_content')): ?>
                                            <div class="template-3__left--sub-content">
                                                <?php echo $sub_content ?>
                                            </div>
                                        <?php endif; ?>
                                        <div class="template-3__left--list">
                                            <?php if ($list = get_field('3_list')):
                                                foreach ($list as $item): ?>
                                                    <div class="template-3__left--list--list-item">
                                                        <div class="template-3__left--list--list-item__icon">
                                                            <?php echo ($icon = $item['icon']) ? wp_get_attachment_image($icon['id'], 'full_hd', false, array('class' => '')) : '' ?>
                                                        </div>
                                                        <div class="template-3__left--list--list-item__description">
                                                            <?php echo $item['description'] ?>
                                                        </div>
                                                    </div>
                                                <?php endforeach; endif; ?>
                                        </div>
                                    </div>
                                    <div class="template-3__right">
                                        <?php if ($step_title = get_field('3_step_title')): ?>
                                            <h6 class="template-3__right--step-title"><?php echo $step_title ?></h6>
                                        <?php endif; ?>
                                        <div class="template-3__right--step">
                                            <?php if ($step = get_field('3_step')):
                                                $n = 1;
                                                foreach ($step as $step_item):

                                                    ?>
                                                    <div class="template-3__right--step__item">
                                                        <div class="template-3__right--step__item--number">
                                                            <?php echo '<p>STEP ' . $n . '</p>'; ?>
                                                        </div>
                                                        <div class="template-3__right--step__item--description">
                                                            <?php echo ($step_item['content']) ?? '' ?>
                                                        </div>
                                                    </div>

                                                    <?php $n++;
                                                endforeach;
                                            endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                <?php endif; ?>
                                <?php if (get_field('Template') == 'Template 2'): ?>
                                <div class="template-2 ">
                                    <div class="grid-container">
                                        <div class="template-2__left">
                                            <div class="template-2__left--content">
                                                <?php echo get_field('2_greencontent') ?? '' ?>
                                            </div>
                                            <?php if ($sub_content = get_field('2_sub_content')): ?>
                                                <div class="template-2__left--sub-content">
                                                    <?php echo $sub_content ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="template-2__right">
                                            <img class="template-1__right__bg" src="<?php echo get_template_directory_uri()?>/assets/images/difference-bg.svg" alt="">

                                            <?php if ($list = get_field('2_list')):
                                                foreach ($list as $item): ?>
                                                    <div class="template-2__right--list-item">
                                                        <div class="template-2__right--list-item__icon">
                                                            <?php echo ($icon = $item['icon']) ? wp_get_attachment_image($icon['id'], 'full_hd', false, array('class' => '')) : '' ?>
                                                        </div>
                                                        <div class="template-2__right--list-item__description">
                                                            <?php echo $item['description'] ?>
                                                        </div>
                                                    </div>
                                                <?php endforeach; endif; ?>
                                        </div>
                                    </div>
                                </div>
                                    <?php endif; ?>

                        <?php if ($services_gallery = get_field('gallery')): ?>
                            <div class="grid-container">
                                <div class="services-archive-posts__gallery">
                                    <div class="services-archive-posts__gallery--slider slider-<?php echo get_post_field('post_name', get_the_ID()); ?>">
                                        <?php foreach ($services_gallery as $slider): ?>
                                            <div class="services-archive-posts__gallery--slider__slide">
                                                <?php if ($slider['video_or_image'] == 'Video'): ?>
                                                    <video id="video" preload="metadata">
                                                        <source src="<?php echo $slider['video_link'] ?? '' ?>"
                                                                type="video/mp4">
                                                    </video>
                                                    <button class="play-button"></button>
                                                <?php endif; ?>
                                                <?php if ($slider['video_or_image'] == 'Image'): ?>
                                                    <?php echo ($image = $slider['image']) ? wp_get_attachment_image($image['id'], 'full_hd', false, array('class' => '')) : '' ?>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php
            endwhile;
        endif; ?>
    </section>


<?php get_footer(); ?>