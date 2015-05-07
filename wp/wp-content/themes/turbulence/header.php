<?php
$options = get_option( 'turbulence_theme_options' );
/**
 * The Header template for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Turbulence
 * @since Turbulence 1.0
 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) & !(IE 8)]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>

    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php wp_title( '|', true, 'right' ); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="<?php echo get_template_directory_uri()?>/apple-touch-icon.png">
    <!-- Place favicon.ico in the root directory -->

    <link rel="stylesheet" href="<?php echo get_template_directory_uri()?>/css/normalize.css">
    <link rel="stylesheet" href="<?php echo get_template_directory_uri()?>/font/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo get_template_directory_uri()?>/css/jquery-ui.min.css">
    <link rel="stylesheet" href="<?php echo get_template_directory_uri()?>/css/jquery-ui.theme.css">
    <link rel="stylesheet" href="<?php echo get_template_directory_uri()?>/css/jquery.bxslider.css">
    <link rel="stylesheet" href="<?php echo get_template_directory_uri()?>/css/main.css">
    <script src="<?php echo get_template_directory_uri()?>/js/vendor/modernizr-2.8.3.min.js"></script>

    <?php wp_head(); ?>

</head>
<?php
    $_thePost =  get_post();
    $_theArtists = get_field('artist');
    $color = get_field('color');
    $_theArtists = is_array($_theArtists) ? $_theArtists : array($_theArtists);
?>

<body <?php echo ($color ? 'class="color-custom" style="color: '.$color.'"' : '')?>>
<div id="wrapper">

<?php if(is_front_page() || $_thePost->post_type == 'commission'):
    $args = array(

        'orderby'   => 'menu_order',
        'order'   => 'ASC',
        'posts_per_page' => -1,
        'post_type'        => 'commission',
        'post_status'      => 'publish',
        'meta_key'		=> 'use_in_main_gallery',
        'meta_value'	=> true
    );

    if($_thePost->post_type == 'commission' && count($_theArtists)) {
         $args['post__in'] = getCommissionsPostIds($_theArtists);
    }

    $mainSlides = get_posts( $args );

    if(count($mainSlides)): ?>
        <div class="main-gallery__helper"></div>

        <div class="main-gallery">
            <div class="main-gallery__content">
                <div class="main-gallery__content-slider">

                    <?php foreach($mainSlides as $ind => $slide): ?>
                        <div class="main-gallery__content-slider-item">
                            <div class="main-gallery__content-inner">
                                <a href="<?php echo get_permalink($slide->ID)?>" class="main-gallery__content-link">
                                    <?php if($value = get_field('use_the_video_in_main_gallery', $slide->ID)):?>
                                        <iframe width="100%" height="490" src="<?php echo $value . (get_field('autoplay_video', $slide->ID) ? '?autoplay=1&cc_load_policy=1' : '');?>" frameborder="0" allowfullscreen></iframe>
                                    <?php else:?>
                                        <?php echo get_the_post_thumbnail( $slide->ID, 'full', array('class'	=> "slide-post-img"))?>
                                    <?php endif;?>

                                </a>
                                <?php if($_thePost->post_type == 'commission' && count($_theArtists)):?>
                                    <div class="main-gallery__content-slider-item-body">
                                        <div class="main-gallery__title"><?php echo get_the_title($slide->ID)?></div>
                                        <div class="main-gallery__author"><?php _e('by')?> <?php
                                            $artists = get_field('artist', $slide->ID);
                                            $artists = is_array($artists) ? $artists : array($artists);
                                            $arr = array();
                                            foreach($artists as $o) {
                                                $arr[] = $o->post_title;
                                            }
                                            echo implode(' && ', $arr);
                                        ?></div>
                                    </div>
                                <?php endif?>
                            </div>
                        </div>
                    <?php endforeach;?>

                </div>
            </div>

            <div class="main-gallery__pager">
                <menu id="main-gallery__pager">
                    <?php foreach($mainSlides as $ind => $slide): ?>
                        <a href="#" data-slide-index="<?php echo $ind?>" <?php echo $ind == 0 ? 'class="active"' : ''?>></a>
                    <?php endforeach;?>
                </menu>
            </div>
        </div>

        <?php if($_thePost->post_type == 'commission'): ?>
            <div class="container main-gallery__header">
                <div class="main-gallery__header-logo"></div>

                <div class="main-gallery__header-form">
                    <form role="search" method="get" id="searchform-main-gallery" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                        <input type="search" value="<?php echo get_search_query(); ?>" name="s"  placeholder="Search or filter by keyword" />
                    </form>
                </div>
            </div>
        <?php endif;?>
        <div class="main-gallery__footer">
            <div class="container">
                <ul class="main-gallery__titles">

                    <?php foreach($mainSlides as $ind => $slide): ?>
                        <li data-slide-index="<?php echo $ind?>" <?php echo $ind == 0 ? 'class="active"' : ''?>>
                            <strong><?php echo $slide->post_title;?></strong>
                            <span><?php
                                    if($artist = get_field('artist', $slide->ID)) {
                                       echo $artist->post_title;
                                    }?>
                        </li>
                    <?php endforeach;?>
                </ul>

                <span class="show-site"></span>
            </div>
        </div>
    <?php endif;?>
<?php endif;?>

    <div id="wrap">

        <?php if($_thePost->post_type == 'commission'): ?>
            <div class="header-helper header-helper_fixed"></div>
        <?php else:?>
            <div class="<?php echo (is_front_page()) ? 'header-helper' : 'header_inner__helper' ?>"></div>
        <?php endif;?>
        <header class="header <?php echo is_front_page() ? 'header_front header_index' : ($_thePost->post_type == 'commission' ? 'header_front header_fixed' : 'header_inner')?>">
            <div class="container">
                <button type="button" class="nav-toggle <?php echo is_front_page() ? 'mobile-vs' : ''?>" data-toggle=".header__nav" data-toggle-dir="ltr"></button>

                <nav class="header__nav">
                    <div class="header__nav-inner">
                        <div class="nav-support header__nav-item">
                            <div class="nav-title"><?php _e('We Support')?></div>

                            <ul class="drop-list">
                                <?php wp_nav_menu(array(
                                    'theme_location'  => '',
                                    'menu'            => 'we_support_nav',
                                    'container'       => '',
                                    'container_class' => '',
                                    'container_id'    => '',
                                    'echo'            => true,
                                    'fallback_cb'     => 'wp_page_menu',
                                    'before'          => '',
                                    'after'           => '',
                                    'link_before'     => '',
                                    'link_after'      => '',
                                    'items_wrap'      => '%3$s',
                                    'depth'           => 0,
                                    'walker'          => ''
                                ));?>
                            </ul>
                        </div>

                        <div class="nav-publish header__nav-item">
                            <div class="nav-title"><?php _e('We Publish')?></div>

                            <ul class="drop-list">
                                <?php wp_nav_menu(array(
                                    'theme_location'  => '',
                                    'menu'            => 'we_publish_nav',
                                    'container'       => '',
                                    'container_class' => '',
                                    'container_id'    => '',
                                    'echo'            => true,
                                    'fallback_cb'     => 'wp_page_menu',
                                    'before'          => '',
                                    'after'           => '',
                                    'link_before'     => '',
                                    'link_after'      => '',
                                    'items_wrap'      => '%3$s',
                                    'depth'           => 0,
                                    'walker'          => ''
                                ));?>
                            </ul>
                        </div>

                        <div class="nav-organize header__nav-item">
                            <div class="nav-title"><?php _e('We Organize')?></div>

                            <ul class="drop-list">
                                <?php wp_nav_menu(array(
                                    'theme_location'  => '',
                                    'menu'            => 'we_organize_nav',
                                    'container'       => '',
                                    'container_class' => '',
                                    'container_id'    => '',
                                    'echo'            => true,
                                    'fallback_cb'     => 'wp_page_menu',
                                    'before'          => '',
                                    'after'           => '',
                                    'link_before'     => '',
                                    'link_after'      => '',
                                    'items_wrap'      => '%3$s',
                                    'depth'           => 0,
                                    'walker'          => ''
                                ));?>
                            </ul>
                        </div>

                        <div class="nav-socialize header__nav-item <?php echo (is_front_page()) ? ' mobile-visible' : '' ?>">
                            <div class="nav-title"><?php _e('We Socialize')?></div>

                            <ul class="drop-list">
                                <?php if($options['email_link']):?>
                                <li><a href="mailto:<?php echo $options['email_link']?>"><i class="icon-mail_small"></i> <?php _e('Email us')?></a></li>
                                <?php endif;?>
                                <?php if($options['facebook_link']):?>
                                <li><a href="<?php echo $options['facebook_link']?>"><i class="icon-fb_small"></i> <?php _e('Like us')?></a></li>
                                <?php endif;?>
                                <?php if($options['twitter_link']):?>
                                <li><a href="<?php echo $options['twitter_link']?>"><i class="icon-tw_small"></i> <?php _e('Follow us')?></a></li>
                                <?php endif;?>
                                <?php if($options['github_link']):?>
                                <li><a href="<?php echo $options['github_link']?>"><i class="icon-github_small"></i> <?php _e('Fork us')?></a></li>
                                <?php endif;?>
                            </ul>
                        </div>
                    </div>

                    <div class="header__search-mobile mobile-visible">
                        <form role="search" method="get" id="searchform-mobile" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                            <input type="search" value="<?php echo get_search_query(); ?>" name="s"  placeholder="Search">
                            <button type="submit"><i class="fa fa-search"></i></button>
                        </form>
                    </div>
                </nav>

                <div class="header__search">

                    <form role="search" method="get" id="searchform" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                        <input type="search" value="<?php echo get_search_query(); ?>" name="s"  placeholder="Search or filter">
                        <button type="submit"><i class="fa fa-search"></i></button>
                    </form>
                </div>
            </div>
        </header>

