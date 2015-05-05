<?php
/**
 * The template for displaying all single posts
 *
 * @package WordPress
 * @subpackage Turbulence
 * @since Turbulence 1.0
 */

get_header(); ?>

    <div id="content">

        <?php /* The loop */ ?>
        <?php while ( have_posts() ) : the_post(); ?>

            <?php get_template_part( 'content', (get_post_type() != 'post' ? get_post_type() : get_post_format()) ); ?>

        <?php endwhile; ?>

    </div><!-- #content -->

<?php get_footer(); ?>