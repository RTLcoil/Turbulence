<?php
/**
 * The default template for displaying content
 *
 * Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage Turbulence
 * @since Turbulence 1.0
 */
?>
<section class="global-section">
    <div class="inner-decor">
        <div class="wrapper">
            <article id="post-<?php the_ID(); ?>" class="top-content mini">
                <h1 class="entry-title">
                    <a  href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
                </h1>

                <?php if ( get_field('page_picture') ) : ?>
                    <div class="entry-picture">
                        <img src="<?php echo get_field('page_picture');?>">
                    </div>
                <?php endif; ?>

                <?php if ( is_search() ) : // Only display Excerpts for Search ?>
                    <div class="entry-summary">
                        <?php the_excerpt(); ?>
                    </div><!-- .entry-summary -->
                <?php else : ?>
                    <div class="entry-content">
                        <?php
                        /* translators: %s: Name of current post */
                        the_content( sprintf(
                            __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'turbulence' ),
                            the_title( '<span class="screen-reader-text">', '</span>', false )
                        ) );

                        wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'turbulence' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) );
                        ?>
                    </div><!-- .entry-content -->
                <?php endif; ?>

                 <a class="more" href="<?php the_permalink(); ?>" rel="bookmark">more</a>

            </article><!-- #post -->
        </div>
    </div>
</section>
