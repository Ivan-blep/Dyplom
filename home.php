<?php
/**
 * Home
 *
 * Standard loop for the blog-page
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
                <h1 class=" animated-element slide-left">
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
<?php
$total_posts = wp_count_posts()->publish;
$posts_per_page = 4;
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$args = array(
    'posts_per_page' => $posts_per_page,
    'paged' => $paged
);
query_posts($args);
?>
    <section class="posts">
        <div class="grid-container">
            <?php while (have_posts()):
                the_post(); ?>
                <?php
                $post_id = get_the_ID();
                $post_date = get_the_time('Y-m-d H:i:s', $post_id);
                $latest_post = get_posts(array('numberposts' => 1));
                $latest_post_date = get_the_time('Y-m-d H:i:s', $latest_post[0]->ID);
                if ($post_date == $latest_post_date) :?>
                    <div class="posts__new-post">
                        <a class="posts__new-post__link" href="<?php the_permalink() ?>"></a>
                        <?php if ($thumbnail_id = get_post_thumbnail_id()): ?>
                            <div class="posts__new-post--image  animated-element slide-left">
                                <div class="new-post"><?php _e('MOST RECENT') ?></div>
                                <?php echo wp_get_attachment_image($thumbnail_id, 'full_hd', false, array('class' => '')); ?>
                            </div>
                        <?php endif; ?>
                        <div class="posts__new-post--content  animated-element slide-right">
                            <div class="posts__new-post--content__container">
                                <?php
                                $category = get_the_category();
                                $date = get_the_date('M j, Y');

                                if ($category && $date):
                                    $category_name = $category[0]->name; ?>
                                    <div class="posts__new-post--content__category">
                                        <?php echo esc_html($category_name) . ' | ' . $date; ?>
                                    </div>
                                <?php endif; ?>
                                <h5 class="posts__new-post--content__title"><?php the_title() ?></h5>
                                <?php if($author = get_field('author')):?>
                                <div class="posts__new-post--content__author">
                                    By <?php echo $author?>
                                </div>
                                <?php endif;?>
                                <div class="posts__new-post--content__excerpt">
                                    <?php if ($excerpt = get_the_excerpt()):
                                        echo $excerpt;
                                    endif; ?>
                                </div>
                            </div>
                            <p class="posts__new-post--content__read-more"><?php _e('READ MORE >') ?></p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="posts__post  animated-element slide-up">
                        <a class="posts__post__link" href="<?php the_permalink() ?>"></a>
                        <?php if ($thumbnail_id = get_post_thumbnail_id()): ?>
                            <div class="posts__post--image">
                                <?php echo wp_get_attachment_image($thumbnail_id, 'full_hd', false, array('class' => '')); ?>
                            </div>
                            <div class="posts__post--content">
                                <h5 class="posts__post--content__title"><?php the_title() ?></h5>
                                <div class="posts__post--content__author">
                                    By <?php the_author(); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endwhile; ?>
            <?php if ($total_posts <= $paged * $posts_per_page) :?>
                <?php else:?>
                <p class="posts__load-more text-right"><a href="#"><?php _e('READ MORE  ')?><i class="fa-solid fa-chevron-down"></i></a></p>
            <?php endif; ?>
        </div>
    </section>




<?php get_footer(); ?>