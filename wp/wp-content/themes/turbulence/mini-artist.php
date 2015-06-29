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
$map = get_field('artist_map');
$ID = get_the_ID();
?>

<section class="i-item_artist mini mini-artist">
  <div class="i-item__popup i-item__popup_person i-item_static">
    <div class="i-item__popup_map">
      <div class="acf-map">
        <div class="marker" data-lat="<?php echo $map['lat']; ?>" data-lng="<?php echo $map['lng']; ?>"></div>
      </div>
    </div>
    <a href="<?php the_permalink();?>" class="i-item__popup-name">
      <img src="<?php echo get_artist_pic($ID,'uri')?>" alt="<?php the_title()?>" class="i-item__popup-artist">
      <span class="i-item__popup-artist-name"><?php  the_title() ?></span>
      <span class="i-item__popup-place"><?php echo get_field('location')?></span>
    </a>
  </div>
</section>
