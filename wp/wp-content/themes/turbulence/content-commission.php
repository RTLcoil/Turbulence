<?php
/**
 * The default template for displaying content
 *
 * Used for both single commission.
 *
 * @package WordPress
 * @subpackage Turbulence
 * @since Turbulence 1.0
 */
$artists = get_field('artist');
$artists = is_array($artists) ? $artists : array($artists)
?>
<section class="commission">
    <div class="commission__work frame-type-<?php echo (get_field('list_frame_type') ? : 'default')?>">
        <?php echo get_the_post_thumbnail(null, array(240,180))?>

        <a href="<?php echo get_field('commission_link') ?>" target"_blank" class="commission__work-overlay"></a>
    </div>

    <h1 class="commission__title"><?php the_title(); ?></h1>
    <div class="commission__author"><?php _e('by')?> <?php

        $arr = array();
        foreach($artists as $o) {
            $arr[] = $o->post_title;
        }
        echo implode(' && ', $arr);
    ?></div>

    <div class="commission__year"><span><?php echo get_field('year_realise')?></span></div>

    <div class="commission__small-desc">
        <?php echo get_the_excerpt()?>
    </div>

    <div class="commission__desc">
        <?php the_content(); ?>
    </div>
    <?php if($media = get_field('media')):?>
        <div class="commission__media">
            <iframe src="<?php echo $media;?>" width="500" height="375" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>

        </div>
    <?php endif;?>

    <div class="commission__item">
        <h3><?php _e('TAXONOMY')?></h3>

        <p>
            <?php
                $tags = array();

                foreach ( wp_get_post_tags(get_the_ID()) as $tag ) {
                    $tag_link = get_tag_link( $tag->term_id );
                    $tags[] = "<a href='{$tag_link}' title='{$tag->name}' class='{$tag->slug}'>{$tag->name}</a>";
                }

                echo implode(' | ', $tags);
            ?>
        </p>
    </div>
    <?php if($requirements = get_field('requirements')):?>
        <div class="commission__item">
            <h3><?php _e('REQUIREMENTS')?></h3>
            <?php echo $requirements;?>
        </div>
    <?php endif;?>

    <?php if($media_and_achievments = get_field('media_and_achievments')):?>
        <div class="commission__item">
            <h3><?php _e('MEDIA &amp; ACHIEVMENTS')?></h3>
            <?php echo $media_and_achievments;?>
        </div>
    <?php endif;?>

    <?php if($addittional_notes = get_field('addittional_notes')):?>
        <div class="commission__item">
            <h3><?php _e('ADDITTIONAL NOTES')?></h3>
            <?php echo $addittional_notes;?>
        </div>
    <?php endif;?>

    <div class="commission__item commission__item_share">
        <h3><?php _e('SHARE')?></h3>

        <div>
            <script>
                document.write('<input type="text" value="' + document.location.href + '" readonly onclick="this.select();">');
            </script>
        </div>

    </div>
</section>
<?php foreach($artists as $artist): ?>
    <section class="artist-details">
        <div class="artist-details__info">
            <h1 class="artist-details__name"><?php echo $artist->post_title?></h1>

            <?php echo get_the_post_thumbnail( $artist->ID, 'full', array('class' => "artist-details__photo"))?>
            <?php if($map = get_field('artist_map', $artist->ID)):?>
                <div class="acf-map">
                    <div class="marker" data-lat="<?php echo $map['lat']; ?>" data-lng="<?php echo $map['lng']; ?>"></div>
                </div>
            <?php else:?>
                <div class="artist-details__map">
                    <img src="<?php echo get_template_directory_uri()?>/img/map-1.png" alt="" class="artist-details__map-img">
                </div>
            <?php endif;?>
            <div class="artist-details__place"><?php echo get_field('location', $artist->ID)?></div>
        </div>

        <div class="artist-details__content">
            <div class="artist-details__desc">
                <?php echo $artist->post_content?>
            </div>

            <?php if($website = get_field('web_site', $artist->ID)):?>
            <a href="http://<?php echo $website?>" target="_blank" class="artist-details__site"><?php echo $website?></a>
            <?php endif;?>

            <div class="artist-details__social">
                <?php if($value = get_field('facebook_link', $artist->ID)):?>
                <a href="<?php echo $value?>" class="social-fb"></a>
                <?php endif;?>

                <?php if($value = get_field('twitter_link', $artist->ID)):?>
                <a href="<?php echo $value?>" class="social-tw"></a>
                <?php endif;?>

                <?php if($value = get_field('github_link', $artist->ID)):?>
                <a href="<?php echo $value?>" class="social-git"></a>
                <?php endif;?>
            </div>

            <?php
            $commissions = get_posts(array(
                'numberposts'	=> 6,
                'exclude'     => get_the_ID(),
                'post_type'		=> 'commission',
                'post__in'      => getCommissionsPostIds(array($artist), array(get_the_ID())),
                'orderby'		=> 'rand',
            ));

            if(count($commissions)):?>

                <div class="artist-details__relevant">
                    <div class="artist-details__relevant-title"><?php _e('Other possibly relevant commissions')?></div>

                    <ul class="artist-details__relevant-list">
                        <?php foreach($commissions as $pItem):?>
                        <li>
                            <a href="<?php echo get_permalink($pItem)?>"><?php echo get_the_post_thumbnail( $pItem->ID, array(50, 50))?></a>
                        </li>
                        <?php endforeach;?>
                    </ul>
                </div>

                <?php endif;?>
        </div>
    </section>
<?php endforeach;?>
