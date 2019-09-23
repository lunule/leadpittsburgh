<?php
/**
 * Lead Pittsburgh functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Lead_Pittsburgh
 */

if ( ! function_exists( 'lead_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function lead_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Lead Pittsburgh, use a find and replace
		 * to change 'lead' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'lead', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'menu-1' => esc_html__( 'Primary', 'lead' ),
			'menu-3' => esc_html__( 'Footer Secondary', 'lead' ),			
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'lead_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );
	}
endif;
add_action( 'after_setup_theme', 'lead_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function lead_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'lead_content_width', 1280 );
}
add_action( 'after_setup_theme', 'lead_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function lead_widgets_init() {

	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'lead' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'lead' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Footbar 1', 'lead' ),
		'id'            => 'footbar-1',
		'description'   => esc_html__( 'Add widgets here.', 'lead' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Footbar 2', 'lead' ),
		'id'            => 'footbar-2',
		'description'   => esc_html__( 'Add widgets here.', 'lead' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Footbar 3', 'lead' ),
		'id'            => 'footbar-3',
		'description'   => esc_html__( 'Add widgets here.', 'lead' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Footbar 4', 'lead' ),
		'id'            => 'footbar-4',
		'description'   => esc_html__( 'Add widgets here.', 'lead' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Footbar 5', 'lead' ),
		'id'            => 'footbar-5',
		'description'   => esc_html__( 'Add widgets here.', 'lead' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );					

}
add_action( 'widgets_init', 'lead_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function lead_scripts() {

	/* ============================================================================================
	# Styles
	============================================================================================ */

	wp_enqueue_style('dashicons');

	wp_enqueue_style( 'lead-googlefonts', 'https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i|Playfair+Display:400,400i,700,700i,900,900i', null, false, 'all' );

	wp_enqueue_style( 'lead-slick', get_template_directory_uri() . '/css/slick.css', null,  false, 'all' );

	wp_enqueue_style( 'lead-slick-theme', get_template_directory_uri() . '/css/slick-theme.css', null,  false, 'all' );

	wp_enqueue_style( 'lead-lightcase', get_template_directory_uri() . '/css/lightcase.css', null,  false, 'all' );	

	wp_enqueue_style( 
		'lead-style', 
		get_stylesheet_uri(), 
		array( 
			'lead-slick', 
			'lead-slick-theme',
			'lead-lightcase',
		),
		false,
		'all' 
	);

	/* ============================================================================================
	# Scripts
	============================================================================================ */

	wp_enqueue_script( 'lead-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	wp_enqueue_script( 'lead-slick', get_template_directory_uri() . '/js/slick.min.js', array( 'jquery' ), false, true );

	wp_enqueue_script( 'lead-counter-bundle', get_template_directory_uri() . '/js/counter-bundle.js', null, false, true );	

	wp_enqueue_script( 'lead-lightcase', get_template_directory_uri() . '/js/lightcase.js', array( 'jquery' ), false, true );

	wp_enqueue_script( 'lead-isotope', 'https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js', array('jquery'), false, true );

	wp_register_script( 
		'lead-global', 
		get_template_directory_uri() . '/js/global.js', 
		array( 
			'jquery', 
			'lead-slick',
			'lead-counter-bundle',
			'lead-lightcase',
			'lead-isotope',
		), 
		false, 
		true 
	);

	$binit 		= get_field('banner_init', 'option');
	$binit 		= is_numeric( $binit ) ? $binit : 3;

	$dbanner 	= get_field('banner_display', 'option');

	$cbanner 	= get_field('banner_content', 'option');

	$translation_array = array(
		'banner_init' 			=> $binit,
		'banner_disabled' 		=> $dbanner ? true : false,
		'banner_has_content'	=> ( $cbanner && ( '' !== $cbanner ) ) ? true : false,
	);
	wp_localize_script( 'lead-global', 'lead', $translation_array );

	wp_enqueue_script( 'lead-global' );	

}
add_action( 'wp_enqueue_scripts', 'lead_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Custom post types.
 */
require get_template_directory() . '/inc/custom-post-types.php';

/**
 * Custom shortcodes.
 */
require get_template_directory() . '/inc/shortcodes.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/* ------------------------------------------------------------------------------------------------
# Images
------------------------------------------------------------------------------------------------ */

/* Deactivate WP's default image optimization behaviour */
add_filter( 'jpeg_quality', function( $arg ) { return 100; } );

/* Custom image sizes */
add_image_size( 'lead-fproject-logo', 231, 9999, true );

// Register custom image sizes for backend use
add_filter( 'image_size_names_choose', 'lead_imgsize_names' );
function lead_imgsize_names( $sizes ) {

	return array_merge( $sizes, array(
		'lead-fproject-logo' => __( 'Featured Project Logo Size (231px width)' ),		
	) );

}

define('ALLOW_UNFILTERED_UPLOADS', true);

/* ------------------------------------------------------------------------------------------------
# Dev helpers
------------------------------------------------------------------------------------------------ */

/**
 * Customize Read More link - CONTENT
 */
function modify_read_more_link() {
    // return '<a class="more-link" href="' . get_permalink() . '">Your Read More Link Text</a>';
    return '';
}
add_filter( 'the_content_more_link', 'modify_read_more_link' );

/**
 * Customize Read More link - EXCERPT
 */
function new_excerpt_more($more) {
       global $post;
	// return '<a class="moretag" href="'. get_permalink($post->ID) . '"> Read the full article...</a>';
	return ' ...';
}
add_filter('excerpt_more', 'new_excerpt_more');

/**
 * Enable shortcodes in text widgets
 */
add_filter('widget_text','do_shortcode');

/**
 * Custom body classes
 */
function lead_custom_body_classes( $classes ) {
	
	global $post;
	$parent_ID = wp_get_post_parent_id( get_the_ID() );	

	if ( is_singular() ) :

		$classes[] = str_replace(' ', '-', strtolower( get_the_title() ));

	endif;

	if ( $parent_ID && 
		 ( 'page-resource-center.php' == get_post_meta( $parent_ID, '_wp_page_template', true ) )
		) :

		$classes[] = 'resource-page';		 

	endif;

	return $classes;

}
add_filter( 'body_class', 'lead_custom_body_classes' );

/**
 * Custom post classes
 */
function lead_custom_post_classes( $classes ) {
	
	global $post;

	if ( is_singular() ) :

		$classes[] = 'chocolat-parent';

	endif;
	
	return $classes;

}
add_filter( 'post_class', 'lead_custom_post_classes' );

/**
 * str_replace modified - replace LAST OCCURRENCE ONLY
 * @see  https://www.jonefox.com/blog/2011/09/26/php-str_replace_last-function/comment-page-1/
 * 
 * @param  [type] $search  [description]
 * @param  [type] $replace [description]
 * @param  [type] $subject [description]
 * @return [type]          [description]
 */
function str_replace_last( $search, $replace, $subject ) {
    if ( !$search || !$replace || !$subject )
        return false;
    
    $index = strrpos( $subject, $search );
    if ( $index === false )
        return $subject;
    
    // Grab everything before occurence
    $pre = substr( $subject, 0, $index );
    
    // Grab everything after occurence
    $post = substr( $subject, $index );
    
    // Do the string replacement
    $post = str_replace( $search, $replace, $post );
    
    // Recombine and return result
    return $pre . $post;
}
/* Usage: 

function some_custom_filter_function( $content ) {

	// Replace the last character ( meaning if the last character is a dot, this custom function will replace ONLY THE LAST DOT OCCURENCE - while the default str_replace function would replace all dots in $value!!! ).

	$readmore_Str 	= __('Read More', 'lead');
	$last_char 		= substr($value, -1, 1);

	return str_replace_last( $last_char, $last_char . ' ' . $readmore_Str, $content);

}
add_filter( 'some_filter_hook', 'some_custom_filter_function' );
*/

/** 
 * Allow links in excerpts
 *
 * WordPress strips out tags in wp_trim_words(), which is called by get_the_excerpt(); 
 * so we have to filter 'wp_trim_words', basically copying that function with one 
 * change: replace wp_strip_all_tags() with strip_tags(). 
 * 
 * We don't want other functions that run wp_trim_words to be modified, so we add our 
 * filter while get_the_excerpt() is running, and remove it when we're done.
 * 
 * @see  https://gist.github.com/swinggraphics/4ca551447bec03da281424c4ff85dcfd
 * 
 */
function lead_trim_words( $text, $num_words, $more, $original_text ) {
	
	$text = strip_tags( $original_text, '<a>' );
	
	// @See wp_trim_words in wp-includes/formatting.php
	if ( strpos( _x( 'words', 'Word count type. Do not translate!' ), 'characters' ) === 0 && preg_match( '/^utf\-?8$/i', get_option( 'blog_charset' ) ) ) :

		$text = trim( preg_replace( "/[\n\r\t ]+/", ' ', $text ), ' ' );
		preg_match_all( '/./u', $text, $words_array );
		$words_array = array_slice( $words_array[0], 0, $num_words + 1 );
		$sep = '';

	else :

		$words_array = preg_split( "/[\n\r\t ]+/", $text, $num_words + 1, PREG_SPLIT_NO_EMPTY );
		$sep = ' ';

	endif;
	
	if ( count( $words_array ) > $num_words ) :

		array_pop( $words_array );
		$text = implode( $sep, $words_array );
		$text = $text . $more;

	else :

		$text = implode( $sep, $words_array );

	endif;
	
	// Remove self so we don't affect other functions that use wp_trim_words
	remove_filter( 'wp_trim_words', 'lead_trim_words' );
	
	return $text;
}

// Be sneaky: add our wp_trim_words filter during excerpt_more filter, which is called 
// immediately prior
function lead_add_trim_words_filter( $excerpt_length ) {
	add_filter( 'wp_trim_words', 'lead_trim_words', 10, 4 );
	return $excerpt_length;
}

add_filter( 'excerpt_more', 'lead_add_trim_words_filter', 1 );

/**
 * Get first image in post
 * @return [type] [description]
 *
 * @see  https://css-tricks.com/snippets/wordpress/get-the-first-image-from-a-post/
 */
function catch_that_image() {

	global $post, $posts;
	
	$first_img = '';
	
	ob_start();
	ob_end_clean();
	
	$output = preg_match_all('/<img.+?src=[\'"]([^\'"]+)[\'"].*?>/i', $post->post_content, $matches);
	$first_img = $matches[1][0];

	if(empty($first_img)) {
		$first_img = "/path/to/default.png";
	}
	return $first_img;

}

/**
 * Remove pages from Search results
 */

function wpb_remove_pages($query) {

  if ( !is_admin() && $query->is_main_query() ) :

    if ($query->is_search) :
		$query->set('post_type', array('post', 'resource'));
    endif;

  endif;

}

add_action('pre_get_posts','wpb_remove_pages');

/**
 * Custom class for next and/or previous posts link
 */
add_filter('next_posts_link_attributes', 'posts_link_attributes');
//add_filter('previous_posts_link_attributes', 'posts_link_attributes');

function posts_link_attributes() {
    return 'class="pagination__next"';
}

if(!function_exists('remove_wpbpb_from_excerpt'))  {
  function remove_wpbpb_from_excerpt( $excerpt ) {
    $patterns = array("/\[[\/]?vc_[^\]]*\]/","/\{[\{] ?vc_[^\}]*\}\}/");
    $replacements = "";
    $clean_excerpt = preg_replace($patterns, $replacements, $excerpt);
    return $clean_excerpt;
  }
}
add_filter( 'the_excerpt', 'remove_wpbpb_from_excerpt' , 11, 1 );

/* ================================================================================================
# Plugins
================================================================================================ */

/* Yoast SEO
------------ */

// Move Yoast Meta Box to bottom
function yoasttobottom() {
	return 'low';
}

add_filter( 'wpseo_metabox_prio', 'yoasttobottom');

/* Gravity Forms
---------------- */

add_filter( 'gform_ajax_spinner_url', 'spinner_url', 10, 2 );

// With a fake value the ugly default spinner gets hidden at least.
function spinner_url( $image_src, $form ) {
    return "http://www.somedomain.com/spinner.png";
}

/* WPBakery Page Builder
------------------------ */

/* Advanced Custom Fields
-------------------------

/**
 * Options page
 * ------------
 */
if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page(array(
		'page_title' 	=> 'LEAD Theme Settings',
		'menu_title'	=> 'LEAD Theme Settings',
		'menu_slug' 	=> 'lead-theme-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> true
	));

	acf_add_options_sub_page(array(
		'page_title' 	=> 'Header Settings',
		'menu_title'	=> 'Header',
		'parent_slug'	=> 'lead-theme-settings',
	));
	
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Contact Settings',
		'menu_title'	=> 'Contact',
		'parent_slug'	=> 'lead-theme-settings',
	));

	acf_add_options_sub_page(array(
		'page_title' 	=> 'Footer Settings',
		'menu_title'	=> 'Footer',
		'parent_slug'	=> 'lead-theme-settings',
	));

	acf_add_options_sub_page(array(
		'page_title' 	=> 'Resource Center Settings',
		'menu_title'	=> 'Resource Center',
		'parent_slug'	=> 'lead-theme-settings',
	));	

	acf_add_options_sub_page(array(
		'page_title' 	=> 'Project Cards',
		'menu_title'	=> 'Project Cards',
		'parent_slug'	=> 'lead-theme-settings',
	));		

	acf_add_options_sub_page(array(
		'page_title' 	=> 'Banner',
		'menu_title'	=> 'Banner',
		'parent_slug'	=> 'lead-theme-settings',
	));			
		
}

/** 
 * Filter the d_resource_on_page Post Object field query (Resource Settings)
 * 
 * @see  https://support.advancedcustomfields.com/forums/topic/populate-post-object-with-posts-using-specific-template/
 */
function filter_d_resource_on_page_query( $args, $field, $post_id ) {

    $args['meta_query'] = array( 
        array(
            'key'   => '_wp_page_template', 
            'value' => 'page-resource-center-subs.php'
        )
    );

	// return
    return $args;
    
}
add_filter('acf/fields/post_object/query/name=d_resource_on_page', 'filter_d_resource_on_page_query', 10, 3);

/** 
 * Filter the d_resource_on_page Post Object field query (Resource Settings)
 * 
 * @see  https://support.advancedcustomfields.com/forums/topic/populate-post-object-with-posts-using-specific-template/
 */
function filter_hide_resource_category_query( $args, $field, $post_id ) {

    $args['meta_query'] = array( 
        array(
            'key'   => '_wp_page_template', 
            'value' => 'page-resource-center-subs.php'
        )
    );

	// return
    return $args;
    
}
add_filter('acf/fields/post_object/query/name=hide_resource_category', 'filter_hide_resource_category_query', 10, 3);

/* ================================================================================================
Transferred from TwentyEleven 
================================================================================================ */

/**
 * Set the post excerpt length to 40 words.
 *
 * To override this length in a child theme, remove
 * the filter and add your own function tied to
 * the excerpt_length filter hook.
 *
 * @since Twenty Eleven 1.0
 *
 * @param int $length The number of excerpt characters.
 * @return int The filtered number of characters.
 */
function twentyeleven_excerpt_length( $length ) {
	return 45;
}
add_filter( 'excerpt_length', 'twentyeleven_excerpt_length' );

if ( ! function_exists( 'twentyeleven_continue_reading_link' ) ) :
	/**
	 * Return a "Continue Reading" link for excerpts
	 *
	 * @since Twenty Eleven 1.0
	 *
	 * @return string The "Continue Reading" HTML link.
	 */
	function twentyeleven_continue_reading_link() {
		return ' <a class="lead-readmore" href="' . esc_url( get_permalink() ) . '">' . __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'twentyeleven' ) . '</a>';
	}
endif; // twentyeleven_continue_reading_link

/**
 * Replace "[...]" in the Read More link with an ellipsis.
 *
 * The "[...]" is appended to automatically generated excerpts.
 *
 * To override this in a child theme, remove the filter and add your own
 * function tied to the excerpt_more filter hook.
 *
 * @since Twenty Eleven 1.0
 *
 * @param string $more The Read More text.
 * @return The filtered Read More text.
 */
function twentyeleven_auto_excerpt_more( $more ) {
	if ( ! is_admin() ) {
		return ' &hellip;' . twentyeleven_continue_reading_link();
	}
	return $more;
}
add_filter( 'excerpt_more', 'twentyeleven_auto_excerpt_more' );

/**
 * Add a pretty "Continue Reading" link to custom post excerpts.
 *
 * To override this link in a child theme, remove the filter and add your own
 * function tied to the get_the_excerpt filter hook.
 *
 * @since Twenty Eleven 1.0
 *
 * @param string $output The "Continue Reading" link.
 * @return string The filtered "Continue Reading" link.
 */
function twentyeleven_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() && ! is_admin() ) {
		$output .= twentyeleven_continue_reading_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'twentyeleven_custom_excerpt_more' );
