<?php
/**
 * Single
 *
 * Loop container for single post content
 */
get_header(); ?>
<?php if ($about_hero_image = get_field('blog_hero_image', 'options')): ?>
    <div class="single-hero-image">
        <div class="single-hero-image__container">
            <?php echo wp_get_attachment_image($about_hero_image['id'], 'full_hd', false, array('class' => '')); ?>
        </div>
    </div>
<?php endif; ?>
    <section class="single-hero">
        <div class="single-hero__title">
            <div class="grid-container">
                <h1>
                    <?php
                    $title = get_field('blog_hero_title', 'options');
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
    </section>
    <div class="single">
        <div class="grid-container">

            <?php if ($thumbnail_id = get_post_thumbnail_id()): ?>
                <div class="single__image">
                    <?php
                    $post_id = get_the_ID();
                    $post_date = get_the_time('Y-m-d H:i:s', $post_id);
                    $latest_post = get_posts(array('numberposts' => 1));
                    $latest_post_date = get_the_time('Y-m-d H:i:s', $latest_post[0]->ID);
                    if ($post_date == $latest_post_date) {
                        echo '<div class="new-post">' . __('MOST RECENT') . '</div>';
                    }
                    ?>


                    <?php echo wp_get_attachment_image($thumbnail_id, 'full_hd', false, array('class' => '')); ?>
                </div>

            <?php endif; ?>
            <?php
            $category = get_the_category();
            $date = get_the_date('M j, Y');

            if ($category && $date):
                $category_name = $category[0]->name; ?>
                <div class="single__category">
                    <?php echo esc_html($category_name) . ' | ' . $date; ?>
                </div>
            <?php endif; ?>
            <h4 class="single__title <?php echo ($post_date == $latest_post_date)?'text-uppercase':''?>"> <?php the_title()?></h4>
            <p class="single__subtitle"><?php echo get_field('subtitle')??''?></p>
            <?php the_post(); ?>
            <?php if($author = get_field('author')):?>
            <div class="single__author">
                By <?php echo $author?>
            </div>
            <?php endif;?>
            <div class="single__content">
                <?php the_content()?>
            </div>
            <div class="single__back">
                <p class="text-right"><strong><a href="<?php echo get_post_type_archive_link('post'); ?>">Back <</a></strong></p>

            </div>
        </div>

    </div>


<?php get_footer(); ?>