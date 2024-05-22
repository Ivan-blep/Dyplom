<?php
/**
 * Functions
 */

/******************************************************************************
 * Included Functions
 ******************************************************************************/

// Constants
define('IMAGE_ASSETS', get_stylesheet_directory_uri() . '/assets/images/');
define('IMAGE_PLACEHOLDER', IMAGE_ASSETS . 'placeholder.jpg');

// Helpers function
require_once get_stylesheet_directory() . '/inc/helpers.php';

add_action('init', function () {
    // Register ACF Gravity Forms field
    if (class_exists('ACF')) {
        require_once get_stylesheet_directory() . '/inc/class-acf-field-gravity-v5.php';
    }

    // ACF Gutenberg blocks
    include_once get_stylesheet_directory() . '/inc/gutenberg.php';
}, 9);

/**
 * Prevent Fatal error on site if ACF not installed/activated
 */
function include_acf_placeholder()
{
    include_once get_stylesheet_directory() . '/inc/acf-placeholder.php';
}

add_action('wp', 'include_acf_placeholder', PHP_INT_MAX);

// Install Recommended plugins
require_once get_stylesheet_directory() . '/inc/recommended-plugins.php';
// Walker modification
require_once get_stylesheet_directory() . '/inc/class-starter-navigation.php';
// Home slider function
include_once get_stylesheet_directory() . '/inc/home-slider.php';
// Dynamic admin. Add Custom columns to posts list view
include_once get_stylesheet_directory() . '/inc/class-dynamic-admin.php';
// SVG Support
include_once get_stylesheet_directory() . '/inc/svg-support.php';
// Lazy Load for Images, iframes, videos
include_once get_stylesheet_directory() . '/inc/class-lazyload.php';
// Extend WP Search with Custom fields
include_once get_stylesheet_directory() . '/inc/custom-fields-search.php';
// WooCommerce functionality
if (function_exists('WC')) {
    include_once get_stylesheet_directory() . '/inc/woo-custom.php';
}
// Include all additional shortcodes
//include_once get_stylesheet_directory() . '/inc/shortcodes.php';

/******************************************************************************
 * Global Functions
 ******************************************************************************/

/**
 * WP 5.2 wp_body_open backward compatibility
 */
if (!function_exists('wp_body_open')) {
    function wp_body_open()
    {
        do_action('wp_body_open');
    }
}

// By adding theme support, we declare that this theme does not use a
// hard-coded <title> tag in the document head, and expect WordPress to
// provide it for us.
add_theme_support('title-tag');

//  Add widget support shortcodes
add_filter('widget_text', 'do_shortcode');

// Support for Featured Images
add_theme_support('post-thumbnails');

// Custom Background
add_theme_support('custom-background', array('default-color' => 'fff'));

// Custom Logo
add_theme_support('custom-logo', array(
    'height' => '150',
    'flex-height' => true,
    'flex-width' => true,
));

function show_custom_logo($size = 'medium')
{
    if ($custom_logo_id = get_theme_mod('custom_logo')) {
        $logo_image = wp_get_attachment_image($custom_logo_id, $size, false, array(
            'class' => 'custom-logo skip-lazy',
            'itemprop' => 'siteLogo',
            'alt' => get_bloginfo('name'),
        ));
    } else {
        $logo_url = get_stylesheet_directory_uri() . '/assets/images/custom-logo.png';
        $w = 200;
        $h = 160;
        $logo_image = '<img src="' . $logo_url . '" width="' . $w . '" height="' . $h . '" class="custom-logo" itemprop="siteLogo" alt="' . get_bloginfo('name') . '">';
    }

    $html = sprintf('<a href="%1$s" class="custom-logo-link" rel="home" title="%2$s" itemscope>%3$s</a>', esc_url(home_url('/')), get_bloginfo('name'), $logo_image);
    echo apply_filters('get_custom_logo', $html);
}

// Add HTML5 elements
add_theme_support('html5', array(
    'comment-list',
    'search-form',
    'comment-form',
    'gallery',
    'caption',
    'script',
    'style',
));

// Add RSS Links generation
add_theme_support('automatic-feed-links');
// Hide comments feed link
add_filter('feed_links_show_comments_feed', '__return_false');

// Add excerpt to pages
add_post_type_support('page', 'excerpt');

// Register Navigation Menu
register_nav_menus(array(
    'header-menu' => 'Header Menu',
    'footer-menu' => 'Footer Menu',
));

/**
 * Create pagination
 *
 * @param WP_Query $query
 * @param bool $echo
 * @param null|string $base
 * @param array $args
 *
 * @return string|void
 */
function starter_pagination($query = '', $base = null, $echo = true, $args = [])
{
    if (empty($query)) {
        global $wp_query;
        $query = $wp_query;
    }

    $big = 999999999;
    $pagi_args = array(
        'base' => $base ?: str_replace($big, '%#%', esc_url(explode('?', get_pagenum_link($big), 2)[0])),
        'format' => 'page/%#%',
        'prev_next' => true,
        'prev_text' => '<span class="pagination-arrow fas fa-angle-left"></span>',
        'next_text' => '<span class="pagination-arrow fas fa-angle-right"></span>',
        'current' => max(1, $query->query_vars['paged']),
        'total' => $query->max_num_pages,
        'type' => 'array',
    );

    $args = !empty($_GET) ? array_merge($args, $_GET) : $args;
    if ($args) {
        foreach ($args as $key => $val) {
            $pagi_args['add_args'][$key] = $val;
        }
    }
    $links = paginate_links($pagi_args);
    $pagination = '';

    if ($links) {
        // Add empty prev link
        if ($pagi_args['current'] == 1) {
            array_unshift($links, str_replace('pagination-arrow', 'pagination-arrow disabled', $pagi_args['prev_text']));
        }

        // Add empty next link
        if ($pagi_args['current'] !== 1 && $pagi_args['current'] == $pagi_args['total']) {
            $links[] = str_replace('pagination-arrow', 'pagination-arrow disabled', $pagi_args['next_text']);
        }

        // Pagination container can be replaced with custom.
        // Pagination styles are placed in /scss/inc/_wp-core.scss:136
        $pagination = "<ul class='page-numbers'>\n\t<li>";
        $pagination .= implode("</li>\n\t<li>", $links);
        $pagination .= "</li>\n</ul>\n";

        //		$pagination = str_replace( 'page-numbers', 'pagination', $r );
    }

    if ($echo) {
        echo $pagination;
    } else {
        return $pagination;
    }
}

// Register Sidebars
function starter_widgets_init()
{
    /* Sidebar Right */
    register_sidebar(array(
        'id' => 'starter_sidebar_right',
        'name' => __('Sidebar Right'),
        'description' => __('This sidebar is located on the right-hand side of each page.'),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h5 class="widget__title">',
        'after_title' => '</h5>',
    ));

}

add_action('widgets_init', 'starter_widgets_init');

// Remove #more anchor from posts
function remove_more_jump_link($link)
{
    $offset = strpos($link, '#more-');
    if ($offset) {
        $end = strpos($link, '"', $offset);
    }
    if (!empty($end)) {
        $link = substr_replace($link, '', $offset, $end - $offset);
    }

    return $link;
}

add_filter('the_content_more_link', 'remove_more_jump_link');

// Remove more tag <span> anchor
function remove_more_anchor($content)
{
    return str_replace('<p><span id="more-' . get_the_ID() . '"></span></p>', '', $content);
}

add_filter('the_content', 'remove_more_anchor');


/******************************************************************************************************************************
 * Enqueue Scripts and Styles for Front-End
 *******************************************************************************************************************************/

//Remove jQuery Migrate
function starter_remove_jquery_migrate($scripts)
{
    if (!is_admin() && isset($scripts->registered['jquery'])) {
        $script = $scripts->registered['jquery'];
        if ($script->deps) {
            // Check whether the script has any dependencies
            $script->deps = array_diff($script->deps, array('jquery-migrate'));
        }
    }
}

add_action('wp_default_scripts', 'starter_remove_jquery_migrate');

function starter_scripts_and_styles()
{
    if (!is_admin()) {

        wp_deregister_style('gform_theme_ie11');

        // Load Stylesheets
        wp_enqueue_style('foundation', get_template_directory_uri() . '/assets/css/foundation.css', null, filemtime(get_stylesheet_directory() . '/assets/css/foundation.css'));
        wp_enqueue_style('select2', get_template_directory_uri() . '/assets/css/select2.min.css', null, '4.1.0');
        wp_enqueue_style('custom', get_template_directory_uri() . '/assets/css/custom.css', null, filemtime(get_stylesheet_directory() . '/assets/css/custom.css'));
        wp_enqueue_style('style', get_template_directory_uri() . '/style.css', null, filemtime(get_stylesheet_directory() . '/style.css'));

        // Load JavaScripts
        wp_deregister_script('jquery-migrate');
        wp_enqueue_script('jquery');

//        wp_enqueue_script('foundation.min', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js', null, '6.8.1', true);

        wp_enqueue_script('foundation.min', get_template_directory_uri() . '/assets/js/foundation.min.js', null, '6.8.1', true);
        wp_add_inline_script('foundation.min', 'jQuery(document).foundation();');
        //plugins
        wp_enqueue_script('slick', get_template_directory_uri() . '/assets/js/plugins/slick.min.js', null, '1.8.1', true);
        wp_enqueue_script('youtube', 'https://www.youtube.com/iframe_api');
        wp_enqueue_script('lazyload', get_template_directory_uri() . '/assets/js/plugins/lazyload.min.js', null, '17.8.2', true);
        wp_enqueue_script('select2', get_template_directory_uri() . '/assets/js/plugins/select2.full.min.js', null, '4.1.0', true);
        wp_enqueue_script('fancybox.v3', get_template_directory_uri() . '/assets/js/plugins/jquery.fancybox.v3.js', null, '3.5.7', true);
        //		wp_enqueue_script( 'fancybox.v4', get_template_directory_uri() . '/assets/js/plugins/fancybox.v4.js', null, '4.0.27', true );
        //		wp_enqueue_script( 'jarallax', get_template_directory_uri() . '/assets/js/plugins/jarallax.min.js', null, '2.14.0', true );

        // !!! DO NOT Use MatchHeight unless it's the only option to align blocks! Use Flex-box styles instead !!!
        //		wp_enqueue_script( 'matchHeight', get_template_directory_uri() . '/assets/js/plugins/jquery.matchHeight-min.js', null, '0.7.2', true );

        //custom javascript
        wp_enqueue_script('global', get_template_directory_uri() . '/assets/js/global.js', ['jquery'], filemtime(get_stylesheet_directory() . '/assets/js/global.js'), true); /* This should go first */
        // Additional PHP data that will be accessible in JS files
        $localize_args = [
            'url' => admin_url('admin-ajax.php'),
        ];
        wp_localize_script('global', 'ajax', $localize_args);

    }
}

add_action('wp_enqueue_scripts', 'starter_scripts_and_styles');

add_filter('acf/load_field/type=google_map', function ($field) {
    $google_map_api = 'https://maps.googleapis.com/maps/api/js';
    $api_args = array(
        'key' => get_theme_mod('google_maps_api') ?: 'AIzaSyBgg23TIs_tBSpNQa8RC0b7fuV4SOVN840',
        'language' => 'en',
        'v' => '3.exp',
    );
    wp_enqueue_script('google.maps.api', add_query_arg($api_args, $google_map_api), null, null, true);

    return $field;
});

/******************************************************************************
 * Additional Functions
 *******************************************************************************/

// Specify image sizes that need to be optimized
function specify_sizes_to_optimize($sizes)
{
    if (empty($sizes) || $sizes == 'thumbnail,medium') {
        $sizes = 'thumbnail,medium,medium_large,large,large_high,full_hd,1536x1536,2048x2048';
    }

    return $sizes;
}

add_filter('wbcr/factory/populate_option_allowed_sizes_thumbnail', 'specify_sizes_to_optimize');

// Disable Robin Image optimizer backup
function disabled_image_bckp_by_default()
{
    return !empty(get_option('wbcr_io_backup_origin_images')) ? get_option('wbcr_io_backup_origin_images') : 0;
}

add_filter('wbcr/factory/populate_option_backup_origin_images', 'disabled_image_bckp_by_default');

function disabled_image_bckp_on_init()
{
    update_option('wbcr_io_backup_origin_images', 0);
}

add_action('wbcr/factory/plugin_activated', 'disabled_image_bckp_on_init');

// Disable Robin Image resize image
function disabled_image_resize_by_default()
{
    return !empty(get_option('wbcr_io_resize_larger')) ? get_option('wbcr_io_resize_larger') : 0;
}

add_filter('wbcr/factory/populate_option_resize_larger', 'disabled_image_resize_by_default');

// Enable revisions for all custom post types
add_filter('cptui_user_supports_params', function () {
    return array('revisions');
});

/**
 * Limit number of revisions for all post types
 *
 * @return int
 */
function limit_revisions_number()
{
    return 10;
}

add_filter('wp_revisions_to_keep', 'limit_revisions_number');

// Add ability ro reply to comments
add_filter('wpseo_remove_reply_to_com', '__return_false');

// Enable control over YouTube iframe through API + add unique ID
function add_youtube_iframe_args($html, $url, $args)
{

    /* Modify video parameters. */
    if (strstr($html, 'youtube.com/embed/') && !empty($args['location'])) {
        preg_match_all('|embed/(.*)\?|', $html, $matches);
        $html = str_replace('?feature=oembed', '?feature=oembed&enablejsapi=1&autoplay=1&mute=1&controls=0&loop=1&showinfo=0&rel=0&playlist=' . $matches[1][0], $html);
        $html = str_replace('<iframe', '<iframe rel="0" enablejsapi="1" id=slide-' . get_the_ID(), $html);
    }

    return $html;
}

add_filter('oembed_result', 'add_youtube_iframe_args', 10, 3);

/**
 * Remove author archive pages
 */
function remove_author_archive_page()
{
    global $wp_query;

    if (is_author()) {
        $wp_query->set_404();
        status_header(404);
        // Redirect to homepage
        // wp_redirect(get_option('home'));
    }
}

add_action('template_redirect', 'remove_author_archive_page');

/**
 * Remove comments feed links
 */
add_filter('post_comments_feed_link', '__return_null');

// Stick Admin Bar To The Top
if (!is_admin()) {
    add_theme_support('admin-bar', array('callback' => '__return_false'));

    function stick_admin_bar()
    {
        echo "
			<style>
			@media only screen and (min-width: 1025px) {
				body.admin-bar {margin-top:32px !important}
			}
			@media only screen and (max-width: 1024px) {
				#wpadminbar {display: none;}
			}
			</style>
			";
    }

    add_action('wp_head', 'stick_admin_bar');
}

// Customize Login Screen
function wordpress_login_styling()
{
    if ($custom_logo_id = get_theme_mod('custom_logo')) {
        $custom_logo_img = wp_get_attachment_image_src($custom_logo_id, 'medium');
        $custom_logo_src = $custom_logo_img[0];
    } else {
        $custom_logo_src = 'wp-admin/images/wordpress-logo.svg?ver=20131107';
    }

    $bg_image = get_background_image();

    ?>
    <style type="text/css">
        .login #login h1 a {
            background-image: url('<?php echo $custom_logo_src; ?>');
            background-size: contain;
            background-position: 50% 50%;
            width: auto;
            height: 120px;
            max-width: 320px;
        }

        body.login {
            background-color: #f1f1f1;
            background-repeat: repeat;
            background-position: center center;
        <?php echo $bg_image ? "background-image: url('{$bg_image}') !important;" : ''; ?>
        }
    </style>
<?php }

add_action('login_enqueue_scripts', 'wordpress_login_styling');

function admin_logo_custom_url()
{
    $site_url = get_bloginfo('url');

    return ($site_url);
}

add_filter('login_headerurl', 'admin_logo_custom_url');

/**
 * Display GravityForms fields label if it set to Hidden
 */

function display_gf_fields_label()
{
    echo '<style>.hidden_label label.gfield_label{visibility:visible;line-height:inherit;}.theme-overlay .theme-version{display: none;}</style>';
}

add_action('admin_head', 'display_gf_fields_label');

// ACF Pro Options Page

if (function_exists('acf_add_options_page')) {

    acf_add_options_page(array(
        'page_title' => 'Theme General Settings',
        'menu_title' => 'Theme Settings',
        'menu_slug' => 'theme-general-settings',
        'capability' => 'edit_posts',
        'redirect' => false,
    ));

}

// Set Google Map API key

function set_custom_google_api_key()
{
    acf_update_setting('google_api_key', get_theme_mod('google_maps_api') ?: 'AIzaSyBgg23TIs_tBSpNQa8RC0b7fuV4SOVN840');
}

add_action('acf/init', 'set_custom_google_api_key');

// Disable Emoji

remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_styles', 'print_emoji_styles');
remove_filter('the_content_feed', 'wp_staticize_emoji');
remove_filter('comment_text_rss', 'wp_staticize_emoji');
remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
add_filter('tiny_mce_plugins', 'disable_wp_emojis_in_tinymce');

function disable_wp_emojis_in_tinymce($plugins)
{
    if (is_array($plugins)) {
        return array_diff($plugins, array('wpemoji'));
    } else {
        return array();
    }
}

// Wrap any iframe and emved tag into div for responsive view

function iframe_wrapper($content)
{
    // match any iframes
    $pattern = '~<iframe.*?<\/iframe>|<embed.*?<\/embed>~';
    preg_match_all($pattern, $content, $matches);

    foreach ($matches[0] as $match) {
        // Check if it is a video player iframe
        preg_match('~src="(.*?)"~', $match, $iframe_src);
        if (is_embed_video($iframe_src[1])) {
            // wrap matched iframe with div
            $wrappedframe = '<span class="responsive-embed widescreen">' . $match . '</span>';
            //replace original iframe with new in content
            $content = str_replace($match, $wrappedframe, $content);
        }
    }

    return $content;
}

add_filter('the_content', 'iframe_wrapper');
add_filter('acf_the_content', 'iframe_wrapper');


// Dynamic Admin
if (is_admin()) {
    // $dynamic_admin = new DynamicAdmin();
    //	$dynamic_admin->addField( 'page', 'template', 'Page Template', 'template_detail_field_for_page' );

    // $dynamic_admin->run();
}

// Custom outline color
add_action('wp_head', 'custom_outline_color');

function custom_outline_color()
{
    $outline_color = get_theme_mod('outline_color');
    if ($outline_color) {
        echo "<style>a,input,button,textarea,select{outline-color: {$outline_color}}</style>";
    }
}

// Register Google Maps API key settings in customizer

function register_google_maps_settings($wp_customize)
{
    $wp_customize->add_section('google_maps', array(
        'title' => __('Google Maps', 'default'),
        'priority' => 30,
    ));

    $wp_customize->add_setting('google_maps_api', array(
        'default' => 'AIzaSyBgg23TIs_tBSpNQa8RC0b7fuV4SOVN840',
    ));
    $wp_customize->add_control('google_maps_api', array(
        'label' => __('Google Maps API key', 'default'),
        'section' => 'google_maps',
        'settings' => 'google_maps_api',
        'type' => 'text',
    ));

    $wp_customize->add_setting('outline_color', array());
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'outline_color', array(
        'label' => __('Outline color', 'default'),
        'section' => 'colors',
        'settings' => 'outline_color',
    )));
}

add_action('customize_register', 'register_google_maps_settings');

/**
 * Prevent Heartbeat API fire too often. Default is 5 sec, increase it to 60 sec.
 */
add_filter('heartbeat_settings', function ($settings) {
    $settings['interval'] = 60;

    return $settings;
});

/**
 * Copyright field functionality
 *
 * @param array $field ACF Field settings
 *
 * @return array
 */

function populate_copyright_instructions($field)
{
    $field['instructions'] = 'Input <code>@year</code> to replace static year with dynamic, so it will always shows current year.';

    return $field;
}

add_action('acf/load_field/name=copyright', 'populate_copyright_instructions');

if (!is_admin()) {
    // Replace @year with current year
    add_filter('acf/load_value/name=copyright', function ($value) {
        return str_replace('@year', date('Y'), $value);
    });
}

/**
 * Apply lazyload to whole page content
 */

function lazyload()
{
    ob_start('lazyloadBuffer');
}

add_action('template_redirect', 'lazyload');

/**
 * @param string $html HTML content.
 *
 * @return string
 */
function lazyloadBuffer($html)
{
    $lazy = new CreateLazyImg;
    $buffer = $lazy->ignoreScripts($html);
    $buffer = $lazy->ignoreNoscripts($buffer);

    $html = $lazy->lazyloadImages($html, $buffer);
    $html = $lazy->lazyloadPictures($html, $buffer);
    $html = $lazy->lazyloadVideos($html, $buffer);
    $html = $lazy->lazyloadBackgroundImages($html, $buffer);
    $html = $lazy->lazyloadIframes($html, $buffer);

    return $html;
}

// Disable default WP lazyload
add_filter('wp_lazy_loading_enabled', '__return_false');

/**
 * Remove 'current_page_parent' class from blog page item on any other post type archives
 *
 * @param array $classes list of classes
 * @param WP_Post $item menu item object
 *
 * @return array list of classes
 */

function remove_blog_page_classes($classes, $item)
{
    if ((is_post_type_archive() || !is_singular('post')) && $item->type == 'post_type' && $item->object_id == get_option('page_for_posts')) {
        $classes = array_diff($classes, array('current_page_parent'));
    }

    return $classes;
}

add_filter('nav_menu_css_class', 'remove_blog_page_classes', 10, 2);


/**
 * Custom styles in TinyMCE
 *
 * @param array $buttons
 *
 * @return array
 */

function custom_style_selector($buttons)
{
    array_unshift($buttons, 'styleselect');

    return $buttons;
}

add_filter('mce_buttons_2', 'custom_style_selector');

function starter_update_default_tinymce_settings($init_array)
{
    // Define the style_formats array
    $style_formats = array(
        array(
            'title' => 'Heading 1',
            'classes' => 'h1',
            'selector' => 'h1,h2,h3,h4,h5,h6,p,li',
            'wrapper' => false,
        ),
        array(
            'title' => 'Heading 2',
            'classes' => 'h2',
            'selector' => 'h1,h2,h3,h4,h5,h6,p,li',
            'wrapper' => false,
        ),
        array(
            'title' => 'Heading 3',
            'classes' => 'h3',
            'selector' => 'h1,h2,h3,h4,h5,h6,p,li',
            'wrapper' => false,
        ),
        array(
            'title' => 'Heading 4',
            'classes' => 'h4',
            'selector' => 'h1,h2,h3,h4,h5,h6,p,li',
            'wrapper' => false,
        ),
        array(
            'title' => 'Heading 5',
            'classes' => 'h5',
            'selector' => 'h1,h2,h3,h4,h5,h6,p,li',
            'wrapper' => false,
        ),
        array(
            'title' => 'Heading 6',
            'classes' => 'h6',
            'selector' => 'h1,h2,h3,h4,h5,h6,p,li',
            'wrapper' => false,
        ),
        array(
            'title' => 'Button',
            'classes' => 'button',
            'selector' => 'a',
            'wrapper' => false,
        ),
        array(
            'title' => 'Small text',
            'inline' => 'small',
        ),
        array(
            'title' => 'Two columns',
            'classes' => 'two-columns',
            'selector' => 'p,h1,h2,h3,h4,h5,h6,ul',
        ),
        array(
            'title' => 'Three columns',
            'classes' => 'three-columns',
            'selector' => 'p,h1,h2,h3,h4,h5,h6,ul',
        ),
        array(
            'title' => 'Font beloved',
            'classes' => 'beloved',
            'selector' => 'p,h1,h2,h3,h4,h5,h6,ul',
        ),
        array(
            'title' => 'Uppercase',
            'classes' => 'uppercase',
            'selector' => 'p,h1,h2,h3,h4,h5,h6,ul',
        ),
        array(
            'title' => 'Underline',
            'classes' => 'underline',
            'selector' => 'p,h1,h2,h3,h4,h5,h6,ul',
        ),
        array(
            'title' => 'List',
            'block' => 'div',
            'classes' => 'list',
            'wrapper' => true,
        ),
    );
    $init_array['style_formats'] = json_encode($style_formats);

    // Define Editor styles css version. Preventing hard caching
    $time = filemtime(get_stylesheet_directory() . '/editor-style.css');
    // Add the timestamp
    $init_array['cache_suffix'] = 'v=' . $time;

    // Add custom color to TinyMCE editor text color selector
    $default_colours = '"000000", "Black","993300", "Burnt orange","333300", "Dark olive","003300", "Dark green","003366", "Dark azure","000080", "Navy Blue","333399", "Indigo","333333", "Very dark gray","800000", "Maroon","FF6600", "Orange","808000", "Olive","008000", "Green","008080", "Teal","0000FF", "Blue","666699", "Grayish blue","808080", "Gray","FF0000", "Red","FF9900", "Amber","99CC00", "Yellow green","339966", "Sea green","33CCCC", "Turquoise","3366FF", "Royal blue","800080", "Purple","999999", "Medium gray","FF00FF", "Magenta","FFCC00", "Gold","FFFF00", "Yellow","00FF00", "Lime","00FFFF", "Aqua","00CCFF", "Sky blue","993366", "Brown","C0C0C0", "Silver","FF99CC", "Pink","FFCC99", "Peach","FFFF99", "Light yellow","CCFFCC", "Pale green","CCFFFF", "Pale cyan","99CCFF", "Light sky blue","CC99FF", "Plum","FFFFFF", "White"';

    $custom_colours = '';

    foreach (get_theme_colors() as $color) {
        $custom_colours .= '"' . str_replace('#', '', $color['color']) . '","' . $color['name'] . '",';
    }

    $init_array['textcolor_map'] = '[' . $default_colours . ',' . $custom_colours . ']';
    $init_array['textcolor_rows'] = 6; // expand colour grid to 6 rows

    return $init_array;
}

add_filter('tiny_mce_before_init', 'starter_update_default_tinymce_settings');

// Include styles for TinyMCE editor
add_editor_style();

/**
 * Populate ACF color picker swatches with custom colors
 *
 * @return void
 */
function starter_custom_color_picker_colors()
{
    $site_colors = get_theme_colors();
    $colors = wp_list_pluck($site_colors ?? [], 'color');
    if ($colors) {
        $colors_num = count($colors);
        $default_colors = ['#000', '#fff', '#d33', '#d93', '#ee2', '#81d742', '#1e73be', '#8224e3'];
        array_splice($default_colors, 0, $colors_num, $colors);
        $colors_string = json_encode($default_colors);
        wp_add_inline_script('acf-input', "acf.add_filter('color_picker_args', function( args ){args.palettes={$colors_string}; return args; });");
    }

    wp_add_inline_style('acf-input', "body .iris-picker .iris-palette{box-shadow:inset 0 0 2px rgba(0,0,0,.4)}");
}

add_action('acf/input/admin_enqueue_scripts', 'starter_custom_color_picker_colors');

// Move Yoast Meta Box to bottom

function yoasttobottom()
{
    return 'low';
}

add_filter('wpseo_metabox_prio', 'yoasttobottom');

/**
 * Gravity forms custom functions
 */

/**
 * Enable GF Honeypot for all forms
 *
 * @param $form
 * @param $is_new
 */

function enable_honeypot_on_new_form_creation($form, $is_new)
{
    if ($is_new) {
        $form['enableHoneypot'] = true;
        $form['is_active'] = 1;
        GFAPI::update_form($form);
    }
}

add_action('gform_after_save_form', 'enable_honeypot_on_new_form_creation', 10, 2);

/**
 * Disable date field autocomplete popup
 *
 * @param string $input field HTML markup
 * @param object $field GForm field object
 *
 * @return string
 */

function gform_remove_date_autocomplete($input, $field)
{
    if (is_admin()) {
        return $input;
    }
    if ($field->type == 'date') {
        $input = str_replace('<input', '<input autocomplete="off" ', $input);
    }

    return $input;
}

add_filter('gform_field_content', 'gform_remove_date_autocomplete', 11, 2);

// Prevent page jumping on form submit
add_filter('gform_confirmation_anchor', '__return_false');

// Show Gravity Form field label appearance dropdown
add_filter('gform_enable_field_label_visibility_settings', '__return_true');

// Replace standard form input with button
function form_submit_button($button, $form)
{
    if ($form['button']['type'] == 'image' && !empty($form['button']['imageUrl'])) {
        return $button;
    }

    preg_match("~value=(?:\"|')(.*?)(?:\"|')~", $button, $matches);

    return str_replace(array('input', '/>'), array('button', '>'), $button) . "{$matches[1]}</button>";
}

add_filter("gform_submit_button", "form_submit_button", 10, 2);
add_filter("gform_next_button", "form_submit_button", 10, 2);
add_filter("gform_previous_button", "form_submit_button", 10, 2);

// Add ADA support on Gravity form error message
function form_submit_error_ada_notice($msg)
{
    return str_replace("class=", "role='alert' class=", $msg);
}

add_filter('gform_validation_message', 'form_submit_error_ada_notice');

// Add ADA support on Gravity form success message
function form_submit_success_ada_notice($msg)
{
    return str_replace("id='gform_confirmation_message", "role='alert' id='gform_confirmation_message", $msg);
}

add_filter('gform_confirmation', 'form_submit_success_ada_notice');

// Prevent loading Gravity forms styles
//add_filter( 'gform_disable_css', '__return_true' );

// Overwrite default gravity forms theme slug
/*add_filter( 'gform_form_theme_slug', function () {
	return 'gravity-theme';
} );*/

// Preselect gravity form legacy theme in Gutenberg block
/*add_action( 'enqueue_block_editor_assets', function () {
	wp_add_inline_script( 'gform_gravityforms_admin', 'wp.hooks.addFilter("blocks.registerBlockType","gravityforms/form",(t,r)=>"gravityforms/form"===r?Object.assign({},t,{attributes:Object.assign({},t.attributes,{theme:{type:"string",default:"gravity-theme"}})}):t);' );
} );*/

/**
 * Remove US phone format from Gravity forms phone field
 *
 * @param array $options List of phone options
 *
 * @return array
 */
function starter_gf_limit_phone_formats($options)
{
    if (!empty($options['standard']) && !empty($options['international'])) {
        $options['standard'] = $options['international'];
        unset($options['international']);
    }

    return $options;
}

add_filter('gform_phone_formats', 'starter_gf_limit_phone_formats');

/**
 * Update Gravity forms title column with instruction
 *
 * @param array $columns List of columns
 *
 * @return array
 */
function starter_update_forms_title_column($columns)
{
    if (!empty($columns['title'])) {
        $columns['title'] = $columns['title'] . ' ' . __('(Use ACF text to output form title, instead of Form title)', 'starter');
    }

    return $columns;
}

add_filter('gform_form_list_columns', 'starter_update_forms_title_column');

/**
 * Allow script and iframe html tags within ACF WYSIWYG editor field
 */

add_filter('acf/the_field/allow_unsafe_html', '__return_true');

/*********************** PUT YOU FUNCTIONS BELOW ********************************/

add_image_size('full_hd', 1920, 0, array('center', 'center'));
add_image_size('large_high', 1024, 0, false);
// add_image_size( 'name', width, height, array('center','center'));

/**
 * Replace WordPress email Sender name
 *
 * @return string
 */
function replace_email_sender_name()
{
    return get_bloginfo();
}

add_filter('wp_mail_from_name', 'replace_email_sender_name');

/**
 * Add WooCommerce support
 */
function theme_add_woocommerce_support()
{
    add_theme_support('woocommerce');
    // add_theme_support( 'wc-product-gallery-zoom' );
    // add_theme_support( 'wc-product-gallery-lightbox' );
    // add_theme_support( 'wc-product-gallery-slider' );
}

add_action('after_setup_theme', 'theme_add_woocommerce_support');

/**
 * Theme main colors.
 *
 * @return array
 */
function get_theme_colors()
{
    // Default colors fallback.
    // TODO Fill $palette array with main design colors to be able use in Gutenberg editor
    $palette = [
        ["name" => "Black", "slug" => "black", "color" => "#000000"],
        ["name" => "White", "slug" => "white", "color" => "#ffffff"],
        ["name" => "Blue", "slug" => "blue", "color" => "#2d22b4"],
        ["name" => "Dark green", "slug" => "dark-green", "color" => "#004952"],
        ["name" => "Green", "slug" => "green", "color" => "#00483E"],
        ["name" => "Light green", "slug" => "light-green", "color" => "#68BC95"],
        ["name" => "Grey", "slug" => "grey", "color" => "#EBEBEB"],
    ];

    return $palette;
}

/*******************************************************************************/

// Gutenberg should be used by default!!!
// TODO comment acf_gutenberg support if Classic Editor is required  
//add_theme_support( 'acf_gutenberg' );

// TODO uncomment acf_flexible support ONLY if Flexible Content is required by client
//add_theme_support( 'acf_flexible' );


function teamPopup()
{
    $teamId = $_GET['teamId'];
    $popup_text = get_post_field('post_content', $teamId);
    $popup_mame = get_post_field('post_title', $teamId);
    $thumbnail_id = get_post_thumbnail_id($teamId);
    $popup_job = get_field('job_title', $teamId);
    $popup_email = get_field('email', $teamId);
    ?>
        <div class="x-mark">
            <svg xmlns="http://www.w3.org/2000/svg" width="29.364" height="29.364" viewBox="0 0 29.364 29.364">
                <path id="Icon_feather-x" data-name="Icon feather-x" d="M34.122,9,9,34.122M9,9,34.122,34.122" transform="translate(-6.879 -6.879)" fill="none" stroke="#00483e" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"/>
            </svg>
        </div>


    <img class="popup__bg" src="<?php echo get_template_directory_uri()?>/assets/images/doctor-bg.svg" alt="">
    <div class="popup-image">
        <?php echo wp_get_attachment_image($thumbnail_id, 'full_hd', false, array('class' => '')); ?>
    </div>
    <div class="popup-content">
        <?php if ($popup_mame): ?>
            <h4 class="popup-content__name"><?php echo $popup_mame; ?></h4>
        <?php endif; ?>
        <?php if ($popup_job): ?>
            <p class="popup-content__job"><?php echo $popup_job; ?></p>
        <?php endif; ?>
        <?php if ($popup_email): ?>
            <p class="contact-link contact-link--email popup-content__email"><svg xmlns="http://www.w3.org/2000/svg" width="24.91" height="19.7" viewBox="0 0 24.91 19.7">
                    <g id="Icon_feather-mail" data-name="Icon feather-mail" transform="translate(-1.607 -5)">
                        <path id="Path_229" data-name="Path 229" d="M5.212,6h17.7a2.219,2.219,0,0,1,2.212,2.212V21.487A2.219,2.219,0,0,1,22.912,23.7H5.212A2.219,2.219,0,0,1,3,21.487V8.212A2.219,2.219,0,0,1,5.212,6Z" transform="translate(0 0)" fill="none" stroke="#00483e" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                        <path id="Path_230" data-name="Path 230" d="M25.125,9,14.062,16.744,3,9" transform="translate(0 -0.788)" fill="none" stroke="#00483e" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                    </g>
                </svg>
                <a
                        href="mailto:<?php echo $popup_email; ?>"><?php echo $popup_email; ?></a></p>
        <?php endif; ?>
        <?php echo $popup_text ?? ''; ?>
    </div>
    <?php
    wp_reset_postdata();
    wp_die();
}

add_action('wp_ajax_teamPopup', 'teamPopup');
add_action('wp_ajax_nopriv_teamPopup', 'teamPopup');


function custom_post_type_permalink( $permalink, $post, $leavename ) {
    $post_type = get_post_type($post);

    switch( $post_type ) {
        case 'general-dentistry':
        case 'cosmetic-dentistry':
        case 'preventative-dentist':
            $permalink = home_url( "/dental-services/{$post_type}/{$post->post_name}/" );
            break;
    }
    return $permalink;
}
add_filter('post_type_link', 'custom_post_type_permalink', 10, 3);

function custom_rewrite_rules( $rules ) {
    $new_rules = array(
        'dental-services/(general-dentistry|cosmetic-dentistry|preventative-dentist)/([^/]+)/?$' => 'index.php?post_type=$matches[1]&name=$matches[2]'
    );
    return $new_rules + $rules;
}
add_filter('rewrite_rules_array', 'custom_rewrite_rules');

function custom_post_permalink( $permalink, $post, $leavename ) {

    $post_type = get_post_type($post);

    if ( $post_type == 'post' ) {

        $archive_slug = 'blog';

        $permalink = home_url( "/{$archive_slug}/{$post->post_name}/" );
    }

    return $permalink;
}
add_filter('post_link', 'custom_post_permalink', 10, 3);
add_filter('post_type_link', 'custom_post_permalink', 10, 3);

function custom_rewrite_rules1( $rules ) {

    $archive_slug = 'blog';

    $new_rules = array(
        "{$archive_slug}/([^/]+)/?$" => 'index.php?name=$matches[1]'
    );

    return $new_rules + $rules;
}
add_filter('rewrite_rules_array', 'custom_rewrite_rules1');



function custom_archive_permalink( $url, $type ) {
    if ( in_array( $type, array( 'general-dentistry', 'cosmetic-dentistry', 'preventative-dentist' ) ) ) {
        $url = home_url( "/dental-services/$type/" );
    }
    return $url;
}
add_filter( 'post_type_archive_link', 'custom_archive_permalink', 10, 2 );

function custom_post_type_archive_rule($rules) {
    $new_rules = array(
        'dental-services/(general-dentistry|cosmetic-dentistry|preventative-dentist)/?$' => 'index.php?post_type=$matches[1]'
    );
    return $new_rules + $rules;
}
add_filter('rewrite_rules_array', 'custom_post_type_archive_rule');

function custom_excerpt_length($length) {
    return 45;
}
add_filter('excerpt_length', 'custom_excerpt_length');

function custom_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'custom_excerpt_more');


add_action('wp_ajax_load_more', 'load_more');
add_action('wp_ajax_nopriv_load_more', 'load_more');

function load_more(){

    $args = array(
        'posts_per_page' => -1,
    );
    query_posts($args);
    ?>
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
                    <div class="posts__new-post--image">
                        <div class="new-post"><?php _e('MOST RECENT') ?></div>
                        <?php echo wp_get_attachment_image($thumbnail_id, 'full_hd', false, array('class' => '')); ?>
                    </div>
                <?php endif; ?>
                <div class="posts__new-post--content">
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
                        <div class="posts__new-post--content__author">
                            By: <?php the_author(); ?>
                        </div>
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
            <div class="posts__post">
                <a class="posts__post__link" href="<?php the_permalink() ?>"></a>
                <?php if ($thumbnail_id = get_post_thumbnail_id()): ?>
                    <div class="posts__post--image">
                        <?php echo wp_get_attachment_image($thumbnail_id, 'full_hd', false, array('class' => '')); ?>
                    </div>
                    <div class="posts__post--content">
                        <h5 class="posts__post--content__title"><?php the_title() ?></h5>
                        <div class="posts__post--content__author">
                            By: <?php the_author(); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php endwhile; ?>
<?php
    wp_reset_postdata();
    wp_die();
}



