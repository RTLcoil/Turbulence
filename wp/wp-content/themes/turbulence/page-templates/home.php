<?php
/**
 * Template Name: Home Page
 * @package WordPress
 * @subpackage Turbulence
 * @since Turbulence 1.0
 */
get_header(); ?>
<div class="container">
<?php
    $options = get_option( 'turbulence_theme_options' );
    $args = array(

        'orderby'   => 'menu_order',
        'order'   => 'ASC',
        'posts_per_page' => -1,
        'post_type'        => 'commission',
        'post_status'      => 'publish',
        'meta_key'        => 'use_in_home_slides',
        'meta_value'    => true
);
    $homeSlides = get_posts( $args );

    if(count($homeSlides)): ?>
        <div class="clearfix">
            <div class="slider-medium">
                <div class="slider-medium__wrap">
                    <?php foreach($homeSlides as $ind => $slide): ?>
                        <div class="slider-medium__item">
                            <div class="slider-medium__item-img">
                                <a title="<?php echo get_the_title($slide->ID)?>" href="<?php echo get_permalink($slide->ID)?>">
                                    <?php echo get_the_post_thumbnail( $slide->ID, array(545, 270), array('class'    => "slide-medium-img"))?>
                                </a>
                            </div>

                            <?php
                              $category = get_the_category($slide->ID);
                              if($category[0]){ echo
                                '<span class="label-category">' .
                                  $category[0]->cat_name .
                                '</span>';
                              }
                            ?>

                        </div>
                    <?php endforeach;?>
                </div>

                <div class="slider-medium__content">
                    <ul class="slider-medium__desc">
                        <?php foreach($homeSlides as $ind => $slide): ?>
                            <li <?php echo $ind == 0 ? 'class="active"' : ''?>>
                                <a title="<?php echo get_the_title($slide->ID)?>" href="<?php echo get_permalink($slide->ID)?>">
                                    <strong><?php echo $slide->post_title;?></strong>
                                    <span><?php echo get_field('sub_title', $slide->ID)?></span>
                                </a>
                            </li>
                        <?php endforeach;?>
                    </ul>
                </div>

                <div class="slider-medium__pager">
                    <?php foreach($homeSlides as $ind => $slide): ?>
                        <a href="#" data-slide-index="<?php echo $ind?>" <?php echo $ind == 0 ? 'class="active"' : ''?>></a>
                    <?php endforeach;?>
                </div>
            </div>

            <div class="front-logo">
                <div class="front-logo__media">
                    <img src="<?php echo get_template_directory_uri()?>/img/logo-1.png" alt="">
                </div>

                <div class="front-logo__social">
                    <?php if($options['email_link']):?>
                        <a href="mailto:<?php echo $options['email_link']?>" class="social-envelope"></a>
                    <?php endif;?>
                    <?php if($options['facebook_link']):?>
                        <a href="<?php echo $options['facebook_link']?>" class="social-fb"></a>
                    <?php endif;?>
                    <?php if($options['twitter_link']):?>
                        <a href="<?php echo $options['twitter_link']?>" class="social-tw"></a>
                    <?php endif;?>
                    <?php if($options['github_link']):?>
                        <a href="<?php echo $options['github_link']?>" class="social-git"></a>
                    <?php endif;?>
                </div>
            </div>
        </div>
    <?php endif;?>
    <?php
        $commissionsItems = array();
        $minYear = 9999;
        $maxYear = 0;
        $years = array();

        foreach(get_posts( array(

            'meta_key'            => 'year_realise',
//            'orderby'            => 'meta_value_num',
//            'order'                => 'DESC',
            'orderby'          => 'post_date',
            'order'            => 'DESC',
            'numberposts'       => -1,
            'post_type'        => 'commission',
            'post_status'      => 'publish'
        )) as $pItem) {

            $entry = array();

            $launch_date = date_create(get_field('year_realise', $pItem->ID));
            $entry['year_realise'] = date_format($launch_date,'Y');
            $entry['color'] = get_field('color', $pItem->ID);

            $frm = get_field('list_frame_type', $pItem->ID);

            if ($frm) {
              $entry['list_frame_type'] = $frm;
            }

            if ( has_post_thumbnail($pItem->ID) ) {
              $entry['thumbnail'] = get_the_post_thumbnail($pItem->ID, array(50, 50), array( 'class' => 'thumbnail' ));
              list($entry['image']) = wp_get_attachment_image_src(get_post_thumbnail_id( $pItem->ID ), 'custom-commision-size');
            } else {
              $default_img = get_template_directory_uri() . '/img/default_profile.png';
              $entry['thumbnail'] = '<img class="thumbnail default_img" src="'. $default_img . '" alt="default image"></img>';
              $entry['image'] = $default_img;
            }

            $entry['title'] = $pItem->post_title;
            $cat = get_the_category($pItem->ID);
            $entry['category'] = $cat[0]->cat_name;
            if ($cat){
              $entry['catslug'] = 'i-item_feature '.$cat[0]->slug;
            }
            $entry['url'] = get_permalink($pItem->ID);

            $arts = get_field('artist', $pItem->ID);
            $arts = is_array($arts) ? $arts : array($arts);

            $entry['author'] = array();

            foreach($arts as $art) {
                $entry['author'][] = $art->post_title;
            }

            $entry['author'] = implode(' && ', $entry['author']);

            $entry['url'] = get_permalink($pItem->ID);

            $entry['tags'] = array();
            $entry['tags_links'] = array();

            foreach ( wp_get_post_tags($pItem->ID) as $tag ) {
                $entry['tags_links'][] = get_tag_link( $tag->term_id );
                $entry['tags'][] = $tag->name;
            }

            $years[] = date_format($launch_date,'Y');
            $commissionsItems[] = $entry;
//            *****************
            $maxYear = $entry['year_realise'] > $maxYear ? $entry['year_realise'] : $maxYear;
            $minYear = $entry['year_realise'] < $minYear ? $entry['year_realise'] : $minYear;

        }

        $theYear = $maxYear;
        $yearsSorters = array();
        $commissionsNumber = count($commissionsItems);
        array_multisort($years,  SORT_DESC, SORT_NUMERIC, $commissionsItems,  SORT_DESC, SORT_NUMERIC);

        foreach($commissionsItems as $pInd => $item) {

            if(!isset($yearsSorters[$item['year_realise']])) {
                $yearsSorters[$item['year_realise']] = array('from' => $pInd, 'to' => $commissionsNumber - $pInd);
            }

            $yearsSorters[$item['year_realise']]['to'] = $commissionsNumber - $pInd;
        }

        /***************************/
        $artistsItems = array();

        foreach(get_posts( array(
            'orderby'          => 'post_title',
            'order'            => 'ASC',
            'post_type'        => 'artist',
            'numberposts'       => -1,
            'post_status'      => 'publish'
        )) as $pInd => $pItem) {

            $entry = array();

            $entry['ID'] = $pItem->ID;
            $entry['thumbnail'] = get_the_post_thumbnail($pItem->ID, array(50, 50));
            list($entry['image']) = wp_get_attachment_image_src(get_post_thumbnail_id( $pItem->ID ), array(200, 200));
            $entry['title'] = iconv('UTF-8', 'US-ASCII//TRANSLIT', $pItem->post_title);
            $entry['url'] = get_permalink($pItem->ID);
            $entry['location'] = get_field('location', $pItem->ID);
            $map = get_field('artist_map', $pItem->ID);
            $entry['map_lat'] = $map ? $map['lat'] : '';
            $entry['map_lng'] = $map ? $map['lng'] : '';;
            $entry['letter'] = strtolower(substr($entry['title'], 0, 1));

            $entry['works'] = array();

            foreach(get_posts(array(
                'numberposts'    => 3,
                'post_type'        => 'commission',
                'post__in'      => getCommissionsPostIds(array($pItem)),
            )) as $pCommission) {

                list($image) = wp_get_attachment_image_src(get_post_thumbnail_id( $pCommission->ID ), array(50, 50));
                $entry['works'][] = $image . '?' .  get_permalink($pCommission);
            }
            $artistsItems[] = $entry;
        }

        $lettersSorters = array();
        $usedLetters = array();
        $theLetter = '';

        $artistsNumber = count($artistsItems);

        foreach($artistsItems as $pInd => $item) {

            $theLetter = $theLetter ? $theLetter : $item['letter'];

            if(!isset($lettersSorters[$item['letter']])) {
                $lettersSorters[$item['letter']] = array('from' => $pInd, 'to' => ($artistsNumber - $pInd));
                $usedLetters[] = $item['letter'];
            }

            $lettersSorters[$item['letter']]['to'] = ($artistsNumber - $pInd);
        }
        sort($usedLetters);
        $firstLetter = array_shift($usedLetters);
        $lastLetter = array_pop($usedLetters);
    ?>
    <section class="search-block">

        <header class="search-block__head">

            <div class="search-block__period">
                <div class="search-block__period-years">
                    <div class="search-block__period-left active">
                        <?php echo $maxYear;?>
                        <span data-before="&rarr;" data-after="&larr;"></span>
                    </div>

                    <div class="search-block__period-right">
                        <?php echo $minYear;?>
                        <span data-before="&rarr;" data-after="&larr;"></span>
                    </div>
                </div>

                <div class="search-block__period-years-titles" style="display: none">
                    <div class="search-block__period-left active">
                        <?php echo $maxYear;?>
                        <span data-before="&rarr;" data-after="&larr;"></span>
                    </div>

                    <div class="search-block__period-right">
                        <?php echo $minYear;?>
                        <span data-before="&rarr;" data-after="&larr;"></span>
                    </div>
                </div>

                <div class="search-block__period-letters" style="display: none">
                    <div class="search-block__period-left active">
                        <?php echo strtoupper($firstLetter)?>
                        <span data-before="&rarr;" data-after="&larr;"></span>
                    </div>

                    <div class="search-block__period-right">
                        <?php echo strtoupper($lastLetter)?>
                        <span data-before="&rarr;" data-after="&larr;"></span>
                    </div>
                </div>
            </div>

            <div class="search-block__field">
                <i class="icon-search"></i>
                <input type="search" placeholder="<?php _e('search or filter by work, artist or keyword')?>">
            </div>
        </header>

        <div class="search-block__filter">
            <div class="clearfix search-block__filter-menu">
                <label class="search-block__filter-item-icons">
                    <input type="radio" name="filter-type" value=".type-icon" data-toggle-block=".search-block__period-years"
                           checked>
                    <i></i>
                    <span><?php _e('icon grid')?></span>
                </label>

                <label class="search-block__filter-item-icons-titls">
                    <input type="radio" name="filter-type" value=".type-icon-title"
                           data-toggle-block=".search-block__period-years-titles">
                    <i></i>
                    <span><?php _e('icons and titles')?></span>
                </label>

                <label class="search-block__filter-item-artists">
                    <input type="radio" name="filter-type" value=".type-artist"
                           data-toggle-block=".search-block__period-letters">
                    <i></i>
                    <span><?php _e('commisioned artists')?></span>
                </label>
            </div>

            <div class="search-block__filter-labels">
                <?php foreach(get_tags() as $tag):?>
                    <label class="filter-label">
                        <input type="checkbox" name="filter-labels" value="<?php echo $tag->name?>">
                        <span><?php echo $tag->name?></span>
                    </label>
                <?php endforeach;?>

            </div>
        </div>

        <div class="search-block__content">
            <div class="isotope">

                <div class="i-item i-item_year type-icon"
                     data-sort-up="<?php echo $yearsSorters[$theYear]['from']?>"
                     data-sort-down="<?php echo $yearsSorters[$theYear]['to']?>"
                     data-search="<?php echo $theYear?>">
                    <?php echo $theYear?>
                </div>

                <?php foreach($commissionsItems as $ind => $item):?>


                    <?php if($theYear != $item['year_realise']): $theYear = $item['year_realise'];?>
                        <div class="i-item i-item_year type-icon"
                             data-sort-up="<?php echo $yearsSorters[$theYear]['from']?>"
                             data-sort-down="<?php echo $yearsSorters[$theYear]['to']?>"
                             data-search="<?php echo $theYear?>">
                            <?php echo $theYear?>
                        </div>
                    <?php endif;?>

                    <a href="<?php echo $item['url']?>" title="<?php echo $item['title']?>" class="i-item i-item_icon type-icon <?php echo $item['list_frame_type'] . ' ' . $item['catslug']; ?>"
                         data-search="<?php echo $item['title']?> <?php echo implode(' ', $item['tags'])?>"
                         <?php echo ($item['color'] ? 'data-custom-color="'.$item['color'].'"' : '') ?>
                         data-sort-up="<?php echo $ind;?>"
                         data-sort-down="<?php echo ($commissionsNumber - $ind);?>"
                         data-img="<?php echo $item['image']?>"
                         data-title="<?php echo $item['title']?>"
                         data-category="<?php echo $item['category']?>"
                         data-catslug="<?php echo $item['catslug']?>"
                         data-author="<?php echo $item['author']?>"
                         data-labels="<?php echo implode(',', $item['tags'])?>"
                         data-labels-url="<?php echo implode(',', $item['tags_links'])?>"
                         data-url="<?php echo $item['url']?>">
                        <?php echo $item['thumbnail']?>
                    </a>
                <?php endforeach;?>

                <?php
                    $theYear = $maxYear;
                ?>
                <div class="i-item i-item_year type-icon-title"
                     data-sort-up-title="<?php echo $yearsSorters[$theYear]['from']?>"
                     data-sort-down-title="<?php echo $yearsSorters[$theYear]['to']?>"
                     data-search="<?php echo $theYear?>">
                    <?php echo $theYear?>
                </div>

                <?php foreach($commissionsItems as $ind => $item):?>

                    <?php if($theYear != $item['year_realise']): $theYear = $item['year_realise'];?>
                        <div class="i-item i-item_year type-icon-title"
                             data-sort-up-title="<?php echo $yearsSorters[$theYear]['from']?>"
                             data-sort-down-title="<?php echo $yearsSorters[$theYear]['to']?>"
                             data-search="<?php echo $theYear?>">
                            <?php echo $theYear?>
                        </div>
                    <?php endif;?>

                    <div title="<?php echo $item['title']?>" class="i-item i-item_icon_title type-icon-title <?php echo $item['list_frame_type'] . ' ' . $item['catslug']; ?>"
                         data-search="<?php echo $item['title']?> <?php echo implode(' ', $item['tags'])?>"
                        <?php echo ($item['color'] ? 'data-custom-color="'.$item['color'].'"' : '') ?>
                         data-sort-up-title="<?php echo $ind;?>"
                         data-sort-down-title="<?php echo ($commissionsNumber - $ind);?>"
                         data-img="<?php echo $item['image']?>"
                         data-title="<?php echo $item['title']?>"
                         data-category="<?php echo $item['category']?>"
                         data-catslug="<?php echo $item['catslug']?>"
                         data-url="<?php echo $item['url']?>"
                         data-author="<?php echo $item['author']?>"
                         data-labels="<?php echo implode(',', $item['tags'])?>"
                         data-labels-url="<?php echo implode(',', $item['tags_links'])?>">
                        <a href="<?php echo $item['url']?>"><?php echo $item['thumbnail']?></a>
                        <a href="<?php echo $item['url']?>"><?php echo $item['title']?></a>
                    </div>

                <?php endforeach;?>

                <div class="i-item i-item_letter type-artist"
                     data-sort-up-letter="<?php echo $lettersSorters[$theLetter]['from']?>"
                     data-sort-down-letter="<?php echo $lettersSorters[$theLetter]['to']?>"
                     data-search="<?php echo $theLetter?>">
                    <?php echo $theLetter?>
                </div>

                <?php foreach($artistsItems as $ind => $item):?>


                    <?php if($theLetter != $item['letter']): $theLetter = $item['letter'];?>
                        <div class="i-item i-item_letter type-artist"
                             data-sort-up-letter="<?php echo $lettersSorters[$theLetter]['from']?>"
                             data-sort-down-letter="<?php echo $lettersSorters[$theLetter]['to']?>"
                             data-search="<?php echo $theLetter?>">
                            <?php echo $theLetter?>
                        </div>
                    <?php endif;?>

                    <div class="i-item i-item_artist type-artist"

                         data-works="<?php echo implode(',', $item['works'])?>"

                         data-labels="<?php echo $item['title']?>"
                         data-map="<?php echo get_template_directory_uri()?>/img/map-2.png"

                         data-sort-up-letter="<?php echo $ind;?>"
                         data-sort-down-letter="<?php echo ($commissionsNumber - $ind);?>"
                         data-search="<?php echo $item['title']?>"
                         data-artist="<?php echo get_artist_pic($item['ID'],'uri')?>"
                         data-name="<?php echo $item['title']?>?<?php echo $item['url']?>"
                         data-url="<?php echo $item['url']?>"
                         data-place="<?php echo $item['location']?>"
                         data-map-lat="<?php echo $item['map_lat']?>"
                         data-map-lng="<?php echo $item['map_lng']?>">
                         <?php echo get_artist_pic($item['ID'],'normal')?>
                        <a href="<?php echo $item['url']?>"><?php echo $item['title']?></a>
                    </div>
                <?php endforeach;?>
            </div>
        </div>

    </section>
</div>
<?php get_footer(); ?>
