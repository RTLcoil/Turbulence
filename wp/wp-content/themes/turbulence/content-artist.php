<?php
/**
 * The default template for displaying content
 *
 * Used for both single artist.
 *
 * @package WordPress
 * @subpackage Turbulence
 * @since Turbulence 1.0
 */
?>
<section class="artist-details">
    <div class="artist-details__info">
        <h1 class="artist-details__name"><?php the_title(); ?></h1>

        <?php echo get_the_post_thumbnail( get_the_ID(), 'full', array('class'	=> "artist-details__photo"))?>
        <div class="artist-details__map">
            <img src="<?php echo get_template_directory_uri()?>/img/map-1.png" alt="" class="artist-details__map-img">
        </div>

        <div class="artist-details__place"><?php echo get_field('location')?></div>
    </div>

    <div class="artist-details__content">
        <div class="artist-details__desc">
            <?php the_content(); ?>
        </div>

        <?php if($website = get_field('web_site')):?>
            <a href="http://<?php echo $website?>" target="_blank" class="artist-details__site"><?php echo $website?></a>
        <?php endif;?>

        <div class="artist-details__social">
            <?php if($value = get_field('facebook_link')):?>
                <a href="<?php echo $value?>" class="social-fb"></a>
            <?php endif;?>

            <?php if($value = get_field('twitter_link')):?>
                <a href="<?php echo $value?>" class="social-tw"></a>
            <?php endif;?>

            <?php if($value = get_field('github_link')):?>
                <a href="<?php echo $value?>" class="social-git"></a>
            <?php endif;?>
        </div>

        <?php
        $commissions = get_posts(array(
            'post_type'		=> 'commission',
            'meta_key'		=> 'artist',
            'meta_value'	=> get_the_ID()
        ));

        if(count($commissions)):?>

            <div class="artist-details__works">
                <?php foreach($commissions as $pItem):?>
                <a href="<?php echo get_permalink($pItem)?>">
                    <?php echo get_the_post_thumbnail( $pItem->ID, array(200, 200))?>
                    <span><?php echo get_the_title($pItem)?><br>(<?php echo get_field('year_realise', $pItem->ID)?>)</span>
                </a>
                <?php endforeach;?>
            </div>

        <?php endif;?>

    </div>
</section>

