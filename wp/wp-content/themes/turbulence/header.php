<?php

$options = get_option( 'turbulence_theme_options' );
setlocale(LC_ALL, 'en_US.UTF8');
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
<?php
$mainSlides = array();
if(is_front_page()):
    $args = array(
        'orderby'   => 'menu_order',
        'order'   => 'ASC',
        'posts_per_page' => -1,
        'post_type'        => 'commission',
        'post_status'      => 'publish',
        'meta_key'        => 'use_in_main_gallery',
        'meta_value'    => true
    );
    $mainSlides = array();

    foreach(get_posts( $args ) as $ind => $slide) {

        $arr = array();
        $artists = get_field('artist', $slide->ID);
        $artists = is_array($artists) ? $artists : array($artists);
        foreach($artists as $o) {
            $arr[] = $o->post_title;
        }

        $mainSlides[] = array(
            'id' => $slide->ID,
            'top_title' => '',
            'top_sub_title' => '',
            'title' => $slide->post_title,
            'sub_title' => count($arr) ? (_('by') . ' ' . implode(' && ', $arr)) : '',
            'video' => (get_field('use_the_video_in_main_gallery', $slide->ID) ? : false),
            'background' => get_field('main_gallery_background', $slide->ID),
            'autoplay_video' => (get_field('use_the_video_in_main_gallery', $slide->ID) ? : false),
            'link' => get_permalink($slide->ID),
            'thumbnail' => get_the_post_thumbnail( $slide->ID, 'full', array('class'    => "slide-post-img")),
        );
    }
endif;?>

<?php if(is_single() && $_thePost->post_type == 'commission'):
    $rows = get_field('commision_gallery');
    $arr = array();
    $artists = get_field('artist');
    $artists = is_array($artists) ? $artists : array($artists);
    foreach($artists as $o) {
        $arr[] = $o->post_title;
    }

    if(is_array($rows))
    {
        foreach($rows as $row)
        {

            $mainSlides[] = array(
                'id' => $row['commision_gallery_slide']['id'],
                'top_title' => $_thePost->post_title,
                'top_sub_title' => count($arr) ? (_('by') . ' ' . implode(' && ', $arr)) : '',
                'title' => $row['commision_gallery_slide']['title'],
                'sub_title' => count($arr) ? (_('by') . ' ' . implode(' && ', $arr)) : '',
                'video' => false,
                'background' => $row['commision_gallery_background'],
                'autoplay_video' => false,
                'link' => '#',
                'thumbnail' => '<img src="'.$row['commision_gallery_slide']['url'].'" alt="'.$row['commision_gallery_slide']['alt'].'">',
            );

        }
    }

endif;

if(count($mainSlides)): ?>
<div class="main-gallery__helper"></div>
<div class="main-gallery">
    <div class="main-gallery__content">
        <div class="main-gallery__content-slider">
            <?php foreach($mainSlides as $ind => $slide): ?>
            <div class="main-gallery__content-slider-item" <?php echo $slide['background'] ? "style='background: {$slide['background']}'" : ''?>>
                <div class="main-gallery__content-inner">
                    <?php if($slide['video']):?>
                    <div class="main-gallery__content-link main-gallery__content-link_video">
                        <div class="main-gallery__content-link_video-inner">
                        <?php
                            $videoUrl = $slide['video'] . ($slide['autoplay_video'] ?
                                (strpos($slide['video'], '?') === false ? '?' : '&') . 'autoplay=1&cc_load_policy=1' : '');
                            echo apply_filters('the_content', "[embed]" . $videoUrl . "[/embed]");
                        ?>
                        </div>
                    </div>
                    <?php else:?>
                    <a href="<?php echo $slide['link']?>" class="main-gallery__content-link">
                        <?php echo $slide['thumbnail']?>
                    </a>
                    <?php endif;?>
                    <?php if($slide['top_title']):?>
                    <div class="main-gallery__content-slider-item-body">
                        <div class="main-gallery__title"><?php echo $slide['top_title'];?></div>
                        <div class="main-gallery__author"><?php echo $slide['top_sub_title'];?></div>
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
<div class="main-gallery__footer">
    <div class="container">
        <ul class="main-gallery__titles">
            <?php foreach($mainSlides as $ind => $slide): ?>
            <li data-slide-index="<?php echo $ind?>" <?php echo $ind == 0 ? 'class="active"' : ''?>>
                <strong><?php echo $slide['title'];?></strong>
                <?php if($slide['title']) :?>
                <span><?php echo $slide['sub_title'];?></span>
                <?php endif?>
            </li>
            <?php endforeach;?>
        </ul>
        <span class="show-site"></span>
    </div>
</div>
    <?php endif;?>

<?php if($_thePost->post_type == 'commission' or is_front_page()): ?>
<!--<div class="container main-gallery__header">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="main-gallery__header-logo"></a>
    <?php edit_post_link( 'edit'); ?>
    <div class="main-gallery__header-form">
        <form role="search" method="get" id="searchform-main-gallery" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
            <input type="search" value="<?php echo get_search_query(); ?>" name="s"  placeholder="Search or filter by keyword" />
        </form>
    </div>
</div>-->
    <?php endif;?>

<div id="wrap">
    <?php if($_thePost->post_type == 'commission'): ?>
    <div class="header-helper header-helper_fixed"></div>
    <?php else:?>
    <div class="<?php echo (is_front_page()) ? 'header-helper' : 'header_inner__helper' ?>"></div>
    <?php endif;?>
    <header class="header header_fixed <?php echo is_front_page() ? 'header_nav-visible' : ''?>" data-old-classes="<?php echo is_front_page() ? 'header_front header_index' : ($_thePost->post_type == 'commission' ? 'header_front header_fixed' : 'header_inner')?>">
        <div class="container">
            <div class="nav-toggle" data-old-class="<?php echo is_front_page() ? 'mobile-vs' : ''?>">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="link-logo"></a>
                <?php edit_post_link( 'edit'); ?> 
                <div class="nav-toggle-btn"  data-toggle=".header__nav" data-toggle-dir="ltr"></div>
            </div>
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
                    <div class="nav-socialize header__nav-item" data-old-class="<?php echo (is_front_page()) ? ' mobile-visible' : '' ?>">
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
