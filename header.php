<?php
/**
 * Header
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <!-- Set up Meta -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta charset="<?php bloginfo('charset'); ?>">

    <!-- Set the viewport width to device width for mobile -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, user-scalable=yes">
    <!-- Remove Microsoft Edge's & Safari phone-email styling -->
    <meta name="format-detection" content="telephone=no,email=no,url=no">
    <!-- Color mobile browser tab -->
    <!--	<meta name="theme-color" content="#4285f4" />-->

    <!-- Add external fonts below (Typekit Only!) -->

    <?php wp_head(); ?>
</head>

<body <?php body_class('no-outline'); ?>>
<?php wp_body_open(); ?>

<!-- <div class="preloader hide-for-medium">
	<div class="preloader__icon"></div>
</div> -->

<!-- BEGIN of header -->
<header class="header">
    <div class="grid-container menu-grid-container">
        <div class="grid-x align-middle">
            <div class="large-3 small-12 cell header__logo-box">
                <div class="logo text-center medium-text-left">
                    <h1><?php show_custom_logo(); ?><span class="css-clip"><?php echo get_bloginfo('name'); ?></span>
                    </h1>
                </div>
                <div class="header__menu-toggle">
                    <div class="title-bar hide-for-large" data-responsive-toggle="main-menu" data-hide-for="large">
                        <button class="menu-icon" type="button" data-toggle aria-label="Menu" aria-controls="main-menu">
                            <span></span></button>
                    </div>
                </div>
            </div>
            <div class="large-9 small-12 cell nav-container">
                <?php if (has_nav_menu('header-menu')) : ?>
                    <nav class="top-bar" id="main-menu">
                        <?php wp_nav_menu(array(
                            'theme_location' => 'header-menu',
                            'menu_class' => 'menu header-menu',
                            'items_wrap' => '<ul id="%1$s" class="%2$s" data-responsive-menu="accordion large-dropdown" data-submenu-toggle="true" data-multi-open="false" data-close-on-click-inside="false">%3$s</ul>',
                            'walker' => new Starter_Navigation()
                        )); ?>
                        <?php if (get_field('remove_header_button_on_this_page') == true): echo '';
                        else:
                            echo ($header_button = get_field('header_button', 'options')) ? acf_link($header_button, 'button') : ''; endif; ?>
                    </nav>

                <?php endif; ?>
            </div>
        </div>
    </div>
</header>
<!-- END of header -->