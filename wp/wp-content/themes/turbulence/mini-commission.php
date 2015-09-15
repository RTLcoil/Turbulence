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
?>

<?php

$ID = get_the_ID();

if ( has_post_thumbnail() ) {
  list($mini['image']) = wp_get_attachment_image_src(get_post_thumbnail_id(), 'custom-commision-size');
} else {
  $default_img = get_template_directory_uri() . '/img/default_profile.png';
  $mini['image'] = $default_img;
}

$cat = get_the_category($ID);
$mini['category'] = $cat[0]->cat_name;
if ($cat[0]->slug == 'commission' or !isset($cat)){
  $mini['catslug'] = 'mini-commission';
} elseif ($cat){
  $mini['catslug'] = 'i-item_feature mini-'.$cat[0]->slug;
}

$arts = get_field('artist', $pItem->ID);
$arts = is_array($arts) ? $arts : array($arts);

$mini['author'] = array();
foreach($arts as $art) {
    $mini['author'][] = $art->post_title;
}
$mini['author'] = implode(' & ', $mini['author']);

?>

<section class="i-item__popup <?php echo $mini['catslug']?> mini mini-commission">

  <div class="i-item__popup" style="box-shadow: 0 0 0 4px <?php echo get_field('location')?>; color: <?php echo get_field('location')?>">
    <a href="<?php the_permalink();?>" class="i-item__popup-img">
      <img src="<?php echo $mini['image'] ?>" alt="" class="i-item__popup-work">
      <span class="label-category"><?php echo $mini['category'] ?></span>
    </a>
    <div class="i-item__popup-title"><?php the_title() ?><span><?php echo $mini['author'] ?></span></div>
    <div class="i-item__popup-tags"><?php the_tags('',' ','') ?></div>
  </div>

</section>
