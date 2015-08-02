<?php

/**

 * Turbulence functions and definitions

 *

 * Sets up the theme and provides some helper functions, which are used in the

 * theme as custom template tags. Others are attached to action and filter

 * hooks in WordPress to change core functionality.

 *

 * When using a child theme (see http://codex.wordpress.org/Theme_Development

 * and http://codex.wordpress.org/Child_Themes), you can override certain

 * functions (those wrapped in a function_exists() call) by defining them first

 * in your child theme's functions.php file. The child theme's functions.php

 * file is included before the parent theme's file, so the child theme

 * functions would be used.

 *

 * Functions that are not pluggable (not wrapped in function_exists()) are

 * instead attached to a filter or action hook.

 *

 * For more information on hooks, actions, and filters, @link http://codex.wordpress.org/Plugin_API

 *

 * @package WordPress

 * @subpackage Turbulence

 * @since Turbulence 1.0

 */



/*

 * Set up the content width value based on the theme's design.

 *

 * @see turbulence_content_width() for template-specific adjustments.

 */

if ( ! isset( $content_width ) )

	$content_width = 604;



/**

 * Add support for a custom header image.

 */

require get_template_directory() . '/inc/custom-header.php';



/**

 * Turbulence only works in WordPress 3.6 or later.

 */

if ( version_compare( $GLOBALS['wp_version'], '3.6-alpha', '<' ) )

	require get_template_directory() . '/inc/back-compat.php';



/**

 * Turbulence setup.

 *

 * Sets up theme defaults and registers the various WordPress features that

 * Turbulence supports.

 *

 * @uses load_theme_textdomain() For translation/localization support.

 * @uses add_editor_style() To add Visual Editor stylesheets.

 * @uses add_theme_support() To add support for automatic feed links, post

 * formats, and post thumbnails.

 * @uses register_nav_menu() To add support for a navigation menu.

 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.

 *

 * @since Turbulence 1.0

 */

function turbulence_setup() {

	/*

	 * Makes Turbulence available for translation.

	 *

	 * Translations can be added to the /languages/ directory.

	 * If you're building a theme based on Turbulence, use a find and

	 * replace to change 'turbulence' to the name of your theme in all

	 * template files.

	 */

	load_theme_textdomain( 'turbulence', get_template_directory() . '/languages' );



	/*

	 * This theme styles the visual editor to resemble the theme style,

	 * specifically font, colors, icons, and column width.

	 */

	add_editor_style( array( 'css/editor-style.css', 'genericons/genericons.css', turbulence_fonts_url() ) );



	// Adds RSS feed links to <head> for posts and comments.

	add_theme_support( 'automatic-feed-links' );



	/*

	 * Switches default core markup for search form, comment form,

	 * and comments to output valid HTML5.

	 */

	add_theme_support( 'html5', array(

		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'

	) );



	/*

	 * This theme supports all available post formats by default.

	 * See http://codex.wordpress.org/Post_Formats

	 */

	add_theme_support( 'post-formats', array(

		'aside', 'audio', 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video'

	) );



	// This theme uses wp_nav_menu() in one location.

	register_nav_menu( 'primary', __( 'Navigation Menu', 'turbulence' ) );



	/*

	 * This theme uses a custom image size for featured images, displayed on

	 * "standard" posts and pages.

	 */

	add_theme_support( 'post-thumbnails' );

	set_post_thumbnail_size( 604, 270, true );



	// This theme uses its own gallery styles.

	add_filter( 'use_default_gallery_style', '__return_false' );

}

add_action( 'after_setup_theme', 'turbulence_setup' );



/**

 * Return the Google font stylesheet URL, if available.

 *

 * The use of Source Sans Pro and Bitter by default is localized. For languages

 * that use characters not supported by the font, the font can be disabled.

 *

 * @since Turbulence 1.0

 *

 * @return string Font stylesheet or empty string if disabled.

 */

function turbulence_fonts_url() {

	$fonts_url = '';



	/* Translators: If there are characters in your language that are not

	 * supported by Source Sans Pro, translate this to 'off'. Do not translate

	 * into your own language.

	 */

	$source_sans_pro = _x( 'on', 'Source Sans Pro font: on or off', 'turbulence' );



	/* Translators: If there are characters in your language that are not

	 * supported by Bitter, translate this to 'off'. Do not translate into your

	 * own language.

	 */

	$bitter = _x( 'on', 'Bitter font: on or off', 'turbulence' );



	if ( 'off' !== $source_sans_pro || 'off' !== $bitter ) {

		$font_families = array();



		if ( 'off' !== $source_sans_pro )

			$font_families[] = 'Source Sans Pro:300,400,700,300italic,400italic,700italic';



		if ( 'off' !== $bitter )

			$font_families[] = 'Bitter:400,700';



		$query_args = array(

			'family' => urlencode( implode( '|', $font_families ) ),

			'subset' => urlencode( 'latin,latin-ext' ),

		);

		$fonts_url = add_query_arg( $query_args, "//fonts.googleapis.com/css" );

	}



	return $fonts_url;

}



/**

 * Enqueue scripts and styles for the front end.

 *

 * @since Turbulence 1.0

 */

function turbulence_scripts_styles() {

	/*

	 * Adds JavaScript to pages with the comment form to support

	 * sites with threaded comments (when in use).

	 */

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )

		wp_enqueue_script( 'comment-reply' );



	// Adds Masonry to handle vertical alignment of footer widgets.

	if ( is_active_sidebar( 'sidebar-1' ) )

		wp_enqueue_script( 'jquery-masonry' );



	// Loads JavaScript file with functionality specific to Turbulence.



	// Add Source Sans Pro and Bitter fonts, used in the main stylesheet.

	wp_enqueue_style( 'turbulence-fonts', turbulence_fonts_url(), array(), null );



	// Add Genericons font, used in the main stylesheet.

	wp_enqueue_style( 'genericons', get_template_directory_uri() . '/genericons/genericons.css', array(), '3.03' );



	// Loads our main stylesheet.

	wp_enqueue_style( 'turbulence-style', get_stylesheet_uri(), array(), '2013-07-18' );



	// Loads the Internet Explorer specific stylesheet.

	wp_enqueue_style( 'turbulence-ie', get_template_directory_uri() . '/css/ie.css', array( 'turbulence-style' ), '2013-07-18' );

	wp_style_add_data( 'turbulence-ie', 'conditional', 'lt IE 9' );

}

add_action( 'wp_enqueue_scripts', 'turbulence_scripts_styles' );



/**

 * Filter the page title.

 *

 * Creates a nicely formatted and more specific title element text for output

 * in head of document, based on current view.

 *

 * @since Turbulence 1.0

 *

 * @param string $title Default title text for current view.

 * @param string $sep   Optional separator.

 * @return string The filtered title.

 */

function turbulence_wp_title( $title, $sep ) {

	global $paged, $page;



	if ( is_feed() )

		return $title;



	// Add the site name.

	$title .= get_bloginfo( 'name', 'display' );



	// Add the site description for the home/front page.

	$site_description = get_bloginfo( 'description', 'display' );

	if ( $site_description && ( is_home() || is_front_page() ) )

		$title = "$title $sep $site_description";



	// Add a page number if necessary.

	if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() )

		$title = "$title $sep " . sprintf( __( 'Page %s', 'turbulence' ), max( $paged, $page ) );



	return $title;

}

add_filter( 'wp_title', 'turbulence_wp_title', 10, 2 );



/**

 * Register two widget areas.

 *

 * @since Turbulence 1.0

 */

function turbulence_widgets_init() {



	register_sidebar( array(

		'name'          => __( 'Main Widget Area', 'turbulence' ),

		'id'            => 'sidebar-1',

		'description'   => __( 'Appears in the footer section of the site.', 'turbulence' ),

		'before_widget' => '<aside id="%1$s" class="widget %2$s">',

		'after_widget'  => '</aside>',

		'before_title'  => '<h3 class="widget-title">',

		'after_title'   => '</h3>',

	) );



	register_sidebar( array(

		'name'          => __( 'Secondary Widget Area', 'turbulence' ),

		'id'            => 'sidebar-2',

		'description'   => __( 'Appears on posts and pages in the sidebar.', 'turbulence' ),

		'before_widget' => '<aside id="%1$s" class="widget %2$s">',

		'after_widget'  => '</aside>',

		'before_title'  => '<h3 class="widget-title">',

		'after_title'   => '</h3>',

	) );

}

add_action( 'widgets_init', 'turbulence_widgets_init' );



if ( ! function_exists( 'turbulence_paging_nav' ) ) :

/**

 * Display navigation to next/previous set of posts when applicable.

 *

 * @since Turbulence 1.0

 */

function turbulence_paging_nav() {

	global $wp_query;



	// Don't print empty markup if there's only one page.

	if ( $wp_query->max_num_pages < 2 )

		return;

	?>

	<nav class="navigation paging-navigation" role="navigation">

		<div class="nav-links">



			<?php if ( get_next_posts_link() ) : ?>

			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'turbulence' ) ); ?></div>

			<?php endif; ?>



			<?php if ( get_previous_posts_link() ) : ?>

			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'turbulence' ) ); ?></div>

			<?php endif; ?>



		</div><!-- .nav-links -->

	</nav><!-- .navigation -->

	<?php

}

endif;



if ( ! function_exists( 'turbulence_post_nav' ) ) :

/**

 * Display navigation to next/previous post when applicable.

*

* @since Turbulence 1.0

*/

function turbulence_post_nav() {

	global $post;



	// Don't print empty markup if there's nowhere to navigate.

	$previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );

	$next     = get_adjacent_post( false, '', false );



	if ( ! $next && ! $previous )

		return;

	?>

	<nav class="navigation post-navigation" role="navigation">

		<h1 class="screen-reader-text"><?php _e( 'Post navigation', 'turbulence' ); ?></h1>

		<div class="nav-links">



			<?php previous_post_link( '%link', _x( '<span class="meta-nav">&larr;</span> %title', 'Previous post link', 'turbulence' ) ); ?>

			<?php next_post_link( '%link', _x( '%title <span class="meta-nav">&rarr;</span>', 'Next post link', 'turbulence' ) ); ?>



		</div><!-- .nav-links -->

	</nav><!-- .navigation -->

	<?php

}

endif;



if ( ! function_exists( 'turbulence_entry_meta' ) ) :

/**

 * Print HTML with meta information for current post: categories, tags, permalink, author, and date.

 *

 * Create your own turbulence_entry_meta() to override in a child theme.

 *

 * @since Turbulence 1.0

 */

function turbulence_entry_meta() {

	if ( is_sticky() && is_home() && ! is_paged() )

		echo '<span class="featured-post">' . __( 'Sticky', 'turbulence' ) . '</span>';



	if ( ! has_post_format( 'link' ) && 'post' == get_post_type() )

		turbulence_entry_date();



	// Translators: used between list items, there is a space after the comma.

	$categories_list = get_the_category_list( __( ', ', 'turbulence' ) );

	if ( $categories_list ) {

		echo '<span class="categories-links">' . $categories_list . '</span>';

	}



	// Translators: used between list items, there is a space after the comma.

	$tag_list = get_the_tag_list( '', __( ', ', 'turbulence' ) );

	if ( $tag_list ) {

		echo '<span class="tags-links">' . $tag_list . '</span>';

	}



	// Post author

	if ( 'post' == get_post_type() ) {

		printf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',

			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),

			esc_attr( sprintf( __( 'View all posts by %s', 'turbulence' ), get_the_author() ) ),

			get_the_author()

		);

	}

}

endif;



if ( ! function_exists( 'turbulence_entry_date' ) ) :

/**

 * Print HTML with date information for current post.

 *

 * Create your own turbulence_entry_date() to override in a child theme.

 *

 * @since Turbulence 1.0

 *

 * @param boolean $echo (optional) Whether to echo the date. Default true.

 * @return string The HTML-formatted post date.

 */

function turbulence_entry_date( $echo = true ) {

	if ( has_post_format( array( 'chat', 'status' ) ) )

		$format_prefix = _x( '%1$s on %2$s', '1: post format name. 2: date', 'turbulence' );

	else

		$format_prefix = '%2$s';



	$date = sprintf( '<span class="date"><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a></span>',

		esc_url( get_permalink() ),

		esc_attr( sprintf( __( 'Permalink to %s', 'turbulence' ), the_title_attribute( 'echo=0' ) ) ),

		esc_attr( get_the_date( 'c' ) ),

		esc_html( sprintf( $format_prefix, get_post_format_string( get_post_format() ), get_the_date() ) )

	);



	if ( $echo )

		echo $date;



	return $date;

}

endif;



if ( ! function_exists( 'turbulence_the_attached_image' ) ) :

/**

 * Print the attached image with a link to the next attached image.

 *

 * @since Turbulence 1.0

 */

function turbulence_the_attached_image() {

	/**

	 * Filter the image attachment size to use.

	 *

	 * @since Twenty thirteen 1.0

	 *

	 * @param array $size {

	 *     @type int The attachment height in pixels.

	 *     @type int The attachment width in pixels.

	 * }

	 */

	$attachment_size     = apply_filters( 'turbulence_attachment_size', array( 724, 724 ) );

	$next_attachment_url = wp_get_attachment_url();

	$post                = get_post();



	/*

	 * Grab the IDs of all the image attachments in a gallery so we can get the URL

	 * of the next adjacent image in a gallery, or the first image (if we're

	 * looking at the last image in a gallery), or, in a gallery of one, just the

	 * link to that image file.

	 */

	$attachment_ids = get_posts( array(

		'post_parent'    => $post->post_parent,

		'fields'         => 'ids',

		'numberposts'    => -1,

		'post_status'    => 'inherit',

		'post_type'      => 'attachment',

		'post_mime_type' => 'image',

		'order'          => 'ASC',

		'orderby'        => 'menu_order ID'

	) );



	// If there is more than 1 attachment in a gallery...

	if ( count( $attachment_ids ) > 1 ) {

		foreach ( $attachment_ids as $attachment_id ) {

			if ( $attachment_id == $post->ID ) {

				$next_id = current( $attachment_ids );

				break;

			}

		}



		// get the URL of the next image attachment...

		if ( $next_id )

			$next_attachment_url = get_attachment_link( $next_id );



		// or get the URL of the first image attachment.

		else

			$next_attachment_url = get_attachment_link( array_shift( $attachment_ids ) );

	}



	printf( '<a href="%1$s" title="%2$s" rel="attachment">%3$s</a>',

		esc_url( $next_attachment_url ),

		the_title_attribute( array( 'echo' => false ) ),

		wp_get_attachment_image( $post->ID, $attachment_size )

	);

}

endif;



/**

 * Return the post URL.

 *

 * @uses get_url_in_content() to get the URL in the post meta (if it exists) or

 * the first link found in the post content.

 *

 * Falls back to the post permalink if no URL is found in the post.

 *

 * @since Turbulence 1.0

 *

 * @return string The Link format URL.

 */

function turbulence_get_link_url() {

	$content = get_the_content();

	$has_url = get_url_in_content( $content );



	return ( $has_url ) ? $has_url : apply_filters( 'the_permalink', get_permalink() );

}



if ( ! function_exists( 'turbulence_excerpt_more' ) && ! is_admin() ) :

/**

 * Replaces "[...]" (appended to automatically generated excerpts) with ...

 * and a Continue reading link.

 *

 * @since Turbulence 1.4

 *

 * @param string $more Default Read More excerpt link.

 * @return string Filtered Read More excerpt link.

 */

function turbulence_excerpt_more( $more ) {

	$link = sprintf( '<a href="%1$s" class="more-link">%2$s</a>',

		esc_url( get_permalink( get_the_ID() ) ),

			/* translators: %s: Name of current post */

			sprintf( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'turbulence' ), '<span class="screen-reader-text">' . get_the_title( get_the_ID() ) . '</span>' )

		);

	return ' &hellip; ' . $link;

}

add_filter( 'excerpt_more', 'turbulence_excerpt_more' );

endif;



/**

 * Extend the default WordPress body classes.

 *

 * Adds body classes to denote:

 * 1. Single or multiple authors.

 * 2. Active widgets in the sidebar to change the layout and spacing.

 * 3. When avatars are disabled in discussion settings.

 *

 * @since Turbulence 1.0

 *

 * @param array $classes A list of existing body class values.

 * @return array The filtered body class list.

 */

function turbulence_body_class( $classes ) {

	if ( ! is_multi_author() )

		$classes[] = 'single-author';



	if ( is_active_sidebar( 'sidebar-2' ) && ! is_attachment() && ! is_404() )

		$classes[] = 'sidebar';



	if ( ! get_option( 'show_avatars' ) )

		$classes[] = 'no-avatars';



	return $classes;

}

add_filter( 'body_class', 'turbulence_body_class' );



/**

 * Adjust content_width value for video post formats and attachment templates.

 *

 * @since Turbulence 1.0

 */

function turbulence_content_width() {

	global $content_width;



	if ( is_attachment() )

		$content_width = 724;

	elseif ( has_post_format( 'audio' ) )

		$content_width = 484;

}

add_action( 'template_redirect', 'turbulence_content_width' );



/**

 * Add postMessage support for site title and description for the Customizer.

 *

 * @since Turbulence 1.0

 *

 * @param WP_Customize_Manager $wp_customize Customizer object.

 */

function turbulence_customize_register( $wp_customize ) {

	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';

	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';

	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

}

add_action( 'customize_register', 'turbulence_customize_register' );



/**

 * Enqueue Javascript postMessage handlers for the Customizer.

 *

 * Binds JavaScript handlers to make the Customizer preview

 * reload changes asynchronously.

 *

 * @since Turbulence 1.0

 */

function turbulence_customize_preview_js() {

	wp_enqueue_script( 'turbulence-customizer', get_template_directory_uri() . '/js/theme-customizer.js', array( 'customize-preview' ), '20141120', true );

}

add_action( 'customize_preview_init', 'turbulence_customize_preview_js' );



function turbulence_theme_page()

{

    ?>

    <div class="section panel">

        <h1>Custom Theme Options</h1>

        <form method="post" enctype="multipart/form-data" action="options.php">

            <?php

            settings_fields('turbulence_theme_options');



            do_settings_sections('turbulence_theme_options.php');

            ?>

            <p class="submit">

                <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />

            </p>



        </form>

    </div>

<?php

}



function turbulence_theme_menu()

{

    add_theme_page( 'Theme Option', 'Theme Options', 'manage_options', 'turbulence_theme_options.php', 'turbulence_theme_page');

}

add_action('admin_menu', 'turbulence_theme_menu');



/**

 * Register the settings to use on the theme options page

 */

add_action( 'admin_init', 'turbulence_register_settings' );



/**

 * Function to register the settings

 */

function turbulence_register_settings()

{

    // Register the settings with Validation callback

    register_setting( 'turbulence_theme_options', 'turbulence_theme_options', 'turbulence_validate_settings' );



    // Add settings section

    add_settings_section( 'turbulence_general_section', 'General', 'turbulence_display_section', 'turbulence_theme_options.php' );



    // Create textbox field

    $fields = array();



    $fields[] = array(

        'type'      => 'text',

        'id'        => 'site_email',

        'name'      => 'site_email',

        'label'      => 'Email',

        'desc'      => '',

        'std'       => '',

        'label_for' => 'site_email',

        'class'     => 'css_class'

    );



    $fields[] = array(

        'type'      => 'text',

        'id'        => 'site_phone',

        'name'      => 'site_phone',

        'label'      => 'Phone',

        'desc'      => '',

        'std'       => '',

        'label_for' => 'site_phone',

        'class'     => 'css_class'

    );



    $fields[] = array(

        'type'      => 'text',

        'id'        => 'site_address',

        'name'      => 'site_address',

        'label'      => 'Address',

        'label_for' => 'site_address',

    );



    $fields[] = array(

        'type'      => 'text',

        'id'        => 'site_copyright',

        'name'      => 'site_copyright',

        'label'      => 'Copyright',

        'label_for' => 'site_copyright',

    );



    $fields[] = array(

        'type'      => 'text',

        'id'        => 'facebook_link',

        'name'      => 'facebook_link',

        'label'      => 'Facebook Link',

        'label_for' => 'facebook_link',

    );



    $fields[] = array(

        'type'      => 'text',

        'id'        => 'twitter_link',

        'name'      => 'twitter_link',

        'label'      => 'Twitte Link',

        'label_for' => 'twitter_link',

    );



    $fields[] = array(

        'type'      => 'text',

        'id'        => 'github_link',

        'name'      => 'github_link',

        'label'      => 'Github Link',

        'label_for' => 'github_link',

    );



    $fields[] = array(

        'type'      => 'text',

        'id'        => 'email_link',

        'name'      => 'email_link',

        'label'     => 'Email Link',

        'label_for' => 'email_link',

    );



    foreach($fields as $field) {

        add_settings_field( $field['id'], $field['label'], 'turbulence_display_setting', 'turbulence_theme_options.php', 'turbulence_general_section', $field );

    }



}



/**

 * Function to add extra text to display on each section

 */

function turbulence_display_section($section){



}



function turbulence_display_setting($args)

{

    extract( $args );



    $option_name = 'turbulence_theme_options';



    $options = get_option( $option_name );



    switch ( $type ) {

        case 'text':

            $options[$id] = stripslashes($options[$id]);

            $options[$id] = esc_attr( $options[$id]);

            echo "<input size='50' class='regular-text$class' type='text' id='$id' name='" . $option_name . "[$id]' value='$options[$id]' />";

            echo ($desc != '') ? "<br /><span class='description'>$desc</span>" : "";

            break;

    }

}



function turbulence_validate_settings($input)

{



    return $input;

}



add_action( 'init', 'turbulence_excerpts_to_pages' );

function turbulence_excerpts_to_pages() {

    add_post_type_support( 'page', 'excerpt' );

}



show_admin_bar( false );

add_filter('show_admin_bar', '__return_false');



add_filter('nav_menu_css_class', 'current_type_nav_class', 10, 2 );

function current_type_nav_class($classes, $item) {

    $post_type = get_post()->post_type;



    if (strtolower($item->title) == $post_type) {

        array_push($classes, 'current-menu-item');

    }



    return $classes;

}



function getCommissionsPostIds($artists, $exclude = array()) {



    $metaQuery = array();

    $metaQueryREGEXP = array();



    foreach($artists as $o) {

        $metaQuery[] =  $o->ID;

        $metaQueryREGEXP[] = "(.*s:[0-9]+:\"{$o->ID}\".*)";

    }



    global $wpdb;

    $res = $wpdb->get_results("SELECT post_id FROM $wpdb->postmeta WHERE

        (meta_value REGEXP '" . implode('|', $metaQueryREGEXP) . "' OR meta_value in (".implode(',', $metaQuery).")) AND meta_key='artist'");



    $postIds = array(0);

    foreach ( $res as $r )

    {

        if(in_array($r->post_id, $exclude)) continue;



        $postIds[] = $r->post_id;

    }



    return $postIds;

}



function turbulence_add_scripts() {

    wp_enqueue_script( 'google-map', 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false', array(), '3', true );

}



add_action( 'wp_enqueue_scripts', 'turbulence_add_scripts' );

add_image_size( 'custom-commision-size', 200, 200, true );

add_image_size( 'thumbnail-commision-size', 50, 50, true );


/* get artist image */
function get_artist_pic($artist_id, $size = 'original') {
	$twitter = get_field('twitter_link', $artist_id);
	$twitter = str_replace('@', '', $twitter); // remove '@' from string
	$email = get_field('artist_email', $artist_id);
	if(has_post_thumbnail($artist_id)) {
		if($size == 'original') {
			return get_the_post_thumbnail( $artist_id, full);
		} elseif ($size == 'normal') {
			return get_the_post_thumbnail( $artist_id, array(50, 50));
		} elseif ($size == 'uri' || $size == 'uri48') {
			$tag = get_the_post_thumbnail( $artist_id, array(73, 73));
			$extracted = preg_replace('/<img [^>]*src=[\'"]([^\'"]+)[\'"][^>]*>/','\\1',$tag);
			return $extracted;
		}	elseif ($size == 'uri-orig') {
			$tag = get_the_post_thumbnail( $artist_id, full);
			$extracted = preg_replace('/<img [^>]*src=[\'"]([^\'"]+)[\'"][^>]*>/','\\1',$tag);
			return $extracted;
		}
	} elseif ($twitter == true ) {
		if ($size == 'uri' || $size == 'uri48') {
			return 'https://twitter.com/' . $twitter . '/profile_image?size=bigger';
			} elseif ($size == 'uri-orig') {
				return 'https://twitter.com/' . $twitter . '/profile_image?size=original';
			} else {
			return '<img src="https://twitter.com/' . $twitter . '/profile_image?size=' . $size . '"></img>';
		}
	} else {
		if($size == 'original') {
			return get_avatar( $email, '200', get_template_directory_uri() . '/img/default_profile.png');
		} elseif($size == 'normal') {
			return get_avatar( $email, '48', get_template_directory_uri() . '/img/default_profile.png');
		} elseif($size == 'uri') {
			$tag = get_avatar( $email, '73', get_template_directory_uri() . '/img/default_profile.png');
			$extracted = preg_replace('/<img [^>]*src=[\'"]([^\'"]+)[\'"][^>]*>/','\\1',$tag);
			return $extracted;
		} elseif($size == 'uri48') {
			$tag = get_avatar( $email, '48', get_template_directory_uri() . '/img/default_profile.png');
			$extracted = preg_replace('/<img [^>]*src=[\'"]([^\'"]+)[\'"][^>]*>/','\\1',$tag);
			return $extracted;
		}	elseif($size == 'uri-orig') {
			$tag = get_avatar( $email, '48', get_template_directory_uri() . '/img/default_profile.png');
			$extracted = preg_replace('/<img [^>]*src=[\'"]([^\'"]+)[\'"][^>]*>/','\\1',$tag);
			return $extracted;
		}
	}
}

function ald_crp_commissions() {

    global $wpdb, $post, $single, $crp_settings;

    // Retrieve the list of posts
    $results = get_crp_posts_id( array(
        'postid' => $post->ID,
        'strict_limit' => TRUE,
    ) );

    $output = '';

    if ( $results ) {
        $output .= '<div class="artist-details__relevant-title">'. __('Related').'</div><ul class="artist-details__relevant-list">';
        foreach ( $results as $result ) {

            $pItem = get_post( $result->ID );
            $arts = get_field('artist', $pItem->ID);
            $arts = is_array($arts) ? $arts : array($arts);

            $author = array();

            foreach($arts as $art) {
                $author[] = $art->post_title;
            }

            $author = implode(' & ', $author);

            $tags = array();
            foreach ( wp_get_post_tags($pItem->ID) as $tag ) {
                $tags[] = "<a href='".get_tag_link( $tag->term_id )."'>{$tag->name}</a>";
            }
            $color = get_field('color', $pItem->ID);
            $color = $color && $color != 'null' ? $color : false;
            list($thumb) = wp_get_attachment_image_src(get_post_thumbnail_id( $pItem->ID ), array(50, 50));
            list($preview) = wp_get_attachment_image_src(get_post_thumbnail_id( $pItem->ID ), 'custom-commision-size');
            $output .= '
                <li>
                    <div
                        class="i-item"
                        data-href="'.get_permalink($pItem->ID).'">
                            <a title="'.$pItem->post_title.'" href="'.get_permalink($pItem->ID).'">
                                <img width="50" height="50" class="wp-post-image" src="'.$thumb.'">
                            </a>
                            <div style="'.($color ? "box-shadow: 0px 0px 0px 4px {$color}; color: {$color};" : '').' display: none;"
                                 class="i-item__popup '.($color ? 'i-item__popup_custom-color' : '').'">
                                <div class="i-item__popup-frame frame-type-'.(get_field('list_frame_type', $pItem->ID) ? get_field('list_frame_type', $pItem->ID) : 'default').'"></div>
                                <a class="i-item__popup-img"
                                   href="'.get_permalink($pItem->ID).'"><img
                                        class="i-item__popup-work" alt=""
                                        src="'.$preview.'"></a>

                                <div class="i-item__popup-title">'.$pItem->post_title.'<span>'.$author.'</span></div>
                                <div class="i-item__popup-tags">'.implode('', $tags).'</div>
                            </div>
                </div>
            </li>';
        } //end of foreach loop
        $output .= '</ul>'; // closing div of 'crp_related'
    }

    echo $output;

}

function filter_search($query) {

    if ($query->is_tag()) {
        $query->set('post_type', array('artist', 'commission'));
    };
    return $query;
};
add_filter('pre_get_posts', 'filter_search');
?>
