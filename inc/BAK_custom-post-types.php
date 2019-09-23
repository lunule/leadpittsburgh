<?php
/* ================================================================================================
# DEFINE CPT - Resource
# FYKI => [ 'show_in_menu' => '' ], because we only want to include this 
# post type in the custom admin menu, see below
================================================================================================ */

/**
 * Register the new post type
 * -------------------------------------
 */
if ( ! function_exists('lead_cpt_resource') ) {

	function lead_cpt_resource() {

		$labels = array(
			'name'                  => _x( 'Resources', 'Post Type General Name', 'lead' ),
			'singular_name'         => _x( 'Resource', 'Post Type Singular Name', 'lead' ),
			'menu_name'             => __( 'Resources', 'lead' ),
			'name_admin_bar'        => __( 'Resources', 'lead' ),
			'archives'              => __( 'Resources Archives', 'lead' ),
			'attributes'            => __( 'Resources Attributes', 'lead' ),
			'parent_item_colon'     => __( 'Parent Resource:', 'lead' ),
			'all_items'             => __( 'All Resources', 'lead' ),
			'add_new_item'          => __( 'Add New Resource', 'lead' ),
			'add_new'               => __( 'Add New', 'lead' ),
			'new_item'              => __( 'New Resource', 'lead' ),
			'edit_item'             => __( 'Edit Resource', 'lead' ),
			'update_item'           => __( 'Update Resource', 'lead' ),
			'view_item'             => __( 'View Resource', 'lead' ),
			'view_items'            => __( 'View Resources', 'lead' ),
			'search_items'          => __( 'Search Resource', 'lead' ),
			'not_found'             => __( 'Not found', 'lead' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'lead' ),
			'featured_image'        => __( 'Featured Image', 'lead' ),
			'set_featured_image'    => __( 'Set featured image', 'lead' ),
			'remove_featured_image' => __( 'Remove featured image', 'lead' ),
			'use_featured_image'    => __( 'Use as featured image', 'lead' ),
			'insert_into_item'      => __( 'Insert into resource', 'lead' ),
			'uploaded_to_this_item' => __( 'Uploaded to this resource', 'lead' ),
			'items_list'            => __( 'Resources list', 'lead' ),
			'items_list_navigation' => __( 'Resources list navigation', 'lead' ),
			'filter_items_list'     => __( 'Filter resources list', 'lead' ),
			'name_admin_bar' 		=> _x( 'Resource', 'add new on admin bar' ),
		);
		$args = array(
			'label'                 => __( 'Resource', 'lead' ),
			'description'           => __( 'Lead Pittsburgh - Resources', 'lead' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions', 'page-attributes', 'custom-fields' ),
			'taxonomies'            => array(),
			'hierarchical'          => true,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => '',
			'menu_position'         => 20,
			'menu_icon'             => 'dashicons-portfolio',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'capability_type'       => 'page',
			'show_in_rest'          => true,
			'rewrite' 				=> array( 
										'slug' => __('resources', 'lead'), 
									   ),
		);

		register_post_type( 'resource', $args );

	}
	add_action( 'init', 'lead_cpt_resource', 0 );

}

/**
 * Resource update messages.
 * -------------------------------------
 * See /wp-admin/edit-form-advanced.php
 *
 * @param 	array $messages 	Existing post update messages.
 * @return 	array 				Amended post update messages with new CPT update messages.
 */
function lead_cpt_resource_update_messages( $messages ) {
	$post             = get_post();
	$post_type        = 'resource';
	$post_type_object = get_post_type_object( $post_type );

	$messages['resource'] = array(
		0  => '', // Unused. Messages start at index 1.
		1  => __( 'Resource updated.', 'lead' ),
		2  => __( 'Custom field updated.', 'lead' ),
		3  => __( 'Custom field deleted.', 'lead' ),
		4  => __( 'Resource updated.', 'lead' ),
		/* translators: %s: date and time of the revision */
		5  => isset( $_GET['revision'] ) ? sprintf( __( 'Resource restored to revision from %s', 'lead' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6  => __( 'Resource published.', 'lead' ),
		7  => __( 'Resource saved.', 'lead' ),
		8  => __( 'Resource submitted.', 'lead' ),
		9  => sprintf(
			__( 'Resource scheduled for: <strong>%1$s</strong>.', 'lead' ),
			// translators: Publish box date format, see http://php.net/date
			date_i18n( __( 'M j, Y @ G:i', 'lead' ), strtotime( $post->post_date ) )
		),
		10 => __( 'Resource draft updated.', 'lead' )
	);

	if ( $post_type_object->publicly_queryable && 'resource' === $post_type ) {
		$permalink = get_permalink( $post->ID );

		$view_link = sprintf( ' <a href="%s">%s</a>', esc_url( $permalink ), __( 'View resource', 'lead' ) );
		$messages[ $post_type ][1] .= $view_link;
		$messages[ $post_type ][6] .= $view_link;
		$messages[ $post_type ][9] .= $view_link;

		$preview_permalink = add_query_arg( 'preview', 'true', $permalink );
		$preview_link = sprintf( ' <a target="_blank" href="%s">%s</a>', esc_url( $preview_permalink ), __( 'Preview resource', 'lead' ) );
		$messages[ $post_type ][8]  .= $preview_link;
		$messages[ $post_type ][10] .= $preview_link;
	}

	return $messages;
}
add_filter( 'post_updated_messages', 'lead_cpt_resource_update_messages' );

/* ================================================================================================
# DEFINE CPT - Resource Page
# FYKI => [ 'show_in_menu' => '' ], because we only want to include this 
# post type in the custom admin menu, see below
================================================================================================ */

/**
 * Register the new post type
 * -------------------------------------
 */
if ( ! function_exists('lead_cpt_resource_page') ) {

	function lead_cpt_resource_page() {

		$labels = array(
			'name'                  => _x( 'Resource Pages', 'Post Type General Name', 'lead' ),
			'singular_name'         => _x( 'Resource Page', 'Post Type Singular Name', 'lead' ),
			'menu_name'             => __( 'Resource Pages', 'lead' ),
			'name_admin_bar'        => __( 'Resource Pages', 'lead' ),
			'archives'              => __( 'Resource Page Archives', 'lead' ),
			'attributes'            => __( 'Resource Page Attributes', 'lead' ),
			'parent_item_colon'     => __( 'Parent Resource Page:', 'lead' ),
			'all_items'             => __( 'All Resource Pages', 'lead' ),
			'add_new_item'          => __( 'Add New Resource Page', 'lead' ),
			'add_new'               => __( 'Add New', 'lead' ),
			'new_item'              => __( 'New Resource Page', 'lead' ),
			'edit_item'             => __( 'Edit Resource Page', 'lead' ),
			'update_item'           => __( 'Update Resource Page', 'lead' ),
			'view_item'             => __( 'View Resource Page', 'lead' ),
			'view_items'            => __( 'View Resource Pages', 'lead' ),
			'search_items'          => __( 'Search Resource Page', 'lead' ),
			'not_found'             => __( 'Not found', 'lead' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'lead' ),
			'featured_image'        => __( 'Featured Image', 'lead' ),
			'set_featured_image'    => __( 'Set featured image', 'lead' ),
			'remove_featured_image' => __( 'Remove featured image', 'lead' ),
			'use_featured_image'    => __( 'Use as featured image', 'lead' ),
			'insert_into_item'      => __( 'Insert into resource page', 'lead' ),
			'uploaded_to_this_item' => __( 'Uploaded to this resource page', 'lead' ),
			'items_list'            => __( 'Resource Pages list', 'lead' ),
			'items_list_navigation' => __( 'Resource Pages list navigation', 'lead' ),
			'filter_items_list'     => __( 'Filter resource pages list', 'lead' ),
			'name_admin_bar' 		=> _x( 'Resource Page', 'add new on admin bar' ),			
		);
		$args = array(
			'label'                 => __( 'Resource Page', 'lead' ),
			'description'           => __( 'Lead Pittsburgh - Resource Pages', 'lead' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'custom-fields' ),
			'taxonomies'            => array(),
			'hierarchical'          => true,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => '',
			'menu_position'         => 20,
			'menu_icon'             => 'dashicons-feedback',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'capability_type'       => 'post',
			'show_in_rest'          => true,
			'rewrite' 				=> array( 
										'slug' => __('resource-pages', 'lead'),
									   ),
		);

		register_post_type( 'resource_page', $args );

	}
	add_action( 'init', 'lead_cpt_resource_page', 0 );

}

/**
 * Resource Page update messages.
 * -------------------------------------
 * See /wp-admin/edit-form-advanced.php
 *
 * @param 	array $messages 	Existing post update messages.
 * @return 	array 				Amended post update messages with new CPT update messages.
 */
function lead_cpt_resource_page_update_messages( $messages ) {
	$post             = get_post();
	$post_type        = 'resource_page';
	$post_type_object = get_post_type_object( $post_type );

	$messages['resource_page'] = array(
		0  => '', // Unused. Messages start at index 1.
		1  => __( 'Resource Page updated.', 'lead' ),
		2  => __( 'Custom field updated.', 'lead' ),
		3  => __( 'Custom field deleted.', 'lead' ),
		4  => __( 'Resource Page updated.', 'lead' ),
		/* translators: %s: date and time of the revision */
		5  => isset( $_GET['revision'] ) ? sprintf( __( 'Resource Page restored to revision from %s', 'lead' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6  => __( 'Resource Page published.', 'lead' ),
		7  => __( 'Resource Page saved.', 'lead' ),
		8  => __( 'Resource Page submitted.', 'lead' ),
		9  => sprintf(
			__( 'Resource Page scheduled for: <strong>%1$s</strong>.', 'lead' ),
			// translators: Publish box date format, see http://php.net/date
			date_i18n( __( 'M j, Y @ G:i', 'lead' ), strtotime( $post->post_date ) )
		),
		10 => __( 'Resource Page draft updated.', 'lead' )
	);

	if ( $post_type_object->publicly_queryable && 'resource_page' === $post_type ) {
		$permalink = get_permalink( $post->ID );

		$view_link = sprintf( ' <a href="%s">%s</a>', esc_url( $permalink ), __( 'View resource page', 'lead' ) );
		$messages[ $post_type ][1] .= $view_link;
		$messages[ $post_type ][6] .= $view_link;
		$messages[ $post_type ][9] .= $view_link;

		$preview_permalink = add_query_arg( 'preview', 'true', $permalink );
		$preview_link = sprintf( ' <a target="_blank" href="%s">%s</a>', esc_url( $preview_permalink ), __( 'Preview resource page', 'lead' ) );
		$messages[ $post_type ][8]  .= $preview_link;
		$messages[ $post_type ][10] .= $preview_link;
	}

	return $messages;
}
add_filter( 'post_updated_messages', 'lead_cpt_resource_page_update_messages' );

/* ================================================================================================
# Create custom taxonomy
================================================================================================ */

function create_resource_taxonomies() {

	// Add new taxonomy, make it hierarchical (like categories)
	$labels = array(
		'name'              => _x( 'Resource Categories', 'taxonomy general name', 'lead' ),
		'singular_name'     => _x( 'Resource Category', 'taxonomy singular name', 'lead' ),
		'search_items'      => __( 'Search Resource Categories', 'lead' ),
		'all_items'         => __( 'All Resource Categories', 'lead' ),
		'parent_item'       => __( 'Parent Resource Category', 'lead' ),
		'parent_item_colon' => __( 'Parent Resource Category:', 'lead' ),
		'edit_item'         => __( 'Edit Resource Category', 'lead' ),
		'update_item'       => __( 'Update Resource Category', 'lead' ),
		'add_new_item'      => __( 'Add New Resource Category', 'lead' ),
		'new_item_name'     => __( 'New Resource Category Name', 'lead' ),
		'menu_name'         => __( 'Resource Category', 'lead' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'resource-category' ),
	);

	register_taxonomy( 'resource_category', array( 'resource' ), $args );

}

add_action( 'init', 'create_resource_taxonomies', 0 );

/* ================================================================================================
# Remove the slug from the resource_page post type's permalink
================================================================================================ */

/**
 * Remove the slug from published post permalinks. Only affect our CPT though.
 */
function sh_remove_cpt_slug( $post_link, $post, $leavename ) {

	if ( in_array( $post->post_type, array( 'resource_page' ) ) || 'publish' == $post->post_status )
		$post_link = str_replace( '/resource-pages/', '/', $post_link );
	
	return $post_link;

}
add_filter( 'post_type_link', 'sh_remove_cpt_slug', 10, 3 );

function sh_parse_request( $query ) {

	// Only loop the main query
	if ( ! $query->is_main_query() ) {
		return;
	}

	// Only loop our very specific rewrite rule match
	if ( 2 != count( $query->query )
		|| ! isset( $query->query['page'] ) )
		return;

	// 'name' will be set if post permalinks are just post_name, otherwise the page rule will match
	if ( ! empty( $query->query['name'] ) ) {
		$query->set( 'post_type', array( 'resource_page' ) );
	}
}
add_action( 'pre_get_posts', 'sh_parse_request' );

/* ================================================================================================
# Create custom menu to include the post types
================================================================================================ */

/*
* Adding a menu to contain the custom post types for frontpage
*/

function lead_admin_menu_resources() {

	add_menu_page(
		__( 'Resources', 'lead' ), 								// Page title
		__( 'Resources', 'lead' ), 								// Menu title
		'administrator', 										// Capability
		'edit.php?post_type=resource', 							// Menu slug

		'', 													// Callback function
		'dashicons-portfolio', 									// Icon URL
		20 														// Position
	);

	add_submenu_page(
		'edit.php?post_type=resource',							// Parent Menu Slug
		__( 'Resources', 'lead' ), 								// Page title
		__( 'Resources', 'lead' ), 								// Menu title
		'administrator', 										// Capability
		'edit.php?post_type=resource',							// Menu slug
		''														// Callback function
	);			
	
	add_submenu_page(
		'edit.php?post_type=resource',
		__( 'Add New Resource', 'lead' ),
		__( 'Add New Resource', 'lead' ),
		'administrator',
		'post-new.php?post_type=resource',
		''
	);			

	add_submenu_page(
		'edit.php?post_type=resource',
		__( 'Resource Categories', 'lead' ),
		__( 'Resource Categories', 'lead' ),
		'administrator',
		'edit-tags.php?taxonomy=resource_category&post_type=resource',
		''
	);			

	add_submenu_page(
		'edit.php?post_type=resource',
		__( 'Resource Pages', 'lead' ),
		__( 'Resource Pages', 'lead' ),
		'administrator',
		'edit.php?post_type=resource_page',
		''
	);	

	add_submenu_page(
		'edit.php?post_type=resource',
		__( 'Add New Resource Page', 'lead' ),
		__( 'Add New Resource Page', 'lead' ),
		'administrator',
		'post-new.php?post_type=resource_page',
		''
	);

}		

add_action( 'admin_menu', 'lead_admin_menu_resources' );

/* ================================================================================================
# Custom category walker for resource_category
================================================================================================ */

/**
 * Custom Walker_Category class
 * @see https://developer.wordpress.org/reference/classes/walker_category/
 */
class Lead_Category_Walker extends Walker_Category {
 
	/**
	 * Starts the element output.
	 *
	 * @since 2.1.0
	 *
	 * @see Walker::start_el()
	 *
	 * @param string $output   Used to append additional content (passed by reference).
	 * @param object $category Category data object.
	 * @param int    $depth    Optional. Depth of category in reference to parents. Default 0.
	 * @param array  $args     Optional. An array of arguments. See wp_list_categories(). Default empty array.
	 * @param int    $id       Optional. ID of the current category. Default 0.
	 */
	public function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
		/** This filter is documented in wp-includes/category-template.php */
		$cat_name = apply_filters(
			'list_cats',
			esc_attr( $category->name ),
			$category
		);

		// Don't generate an element if the category display is disabled for this page via ACF.
		$thispage_val 	= get_field( 'hide_resource_category', 'resource_category_' . $category->term_id );
		
		if ( !empty( $thispage_val ) ) :

			$thispage_Obj  	= (object) $thispage_val[0];
			$thispage_ID 	= $thispage_Obj->ID;

			if ( get_the_ID() == $thispage_ID )
				return;

		endif;		

		// Don't generate an element if the category name is empty.
		if ( '' === $cat_name ) {
			return;
		}
 
		$link = '<a class="undeco" href="' . esc_url( get_term_link( $category ) ) . '" ';
		if ( $args['use_desc_for_title'] && ! empty( $category->description ) ) {
			/**
			 * Filters the category description for display.
			 *
			 * @since 1.2.0
			 *
			 * @param string $description Category description.
			 * @param object $category    Category object.
			 */
			$link .= 'title="' . esc_attr( strip_tags( apply_filters( 'category_description', $category->description, $category ) ) ) . '"';
		}
 
		$link .= '>';
		$link .= $cat_name . '</a>';
 
		if ( ! empty( $args['feed_image'] ) || ! empty( $args['feed'] ) ) {
			$link .= ' ';
 
			if ( empty( $args['feed_image'] ) ) {
				$link .= '(';
			}
 
			$link .= '<a class="undeco" href="' . esc_url( get_term_feed_link( $category->term_id, $category->taxonomy, $args['feed_type'] ) ) . '"';
 
			if ( empty( $args['feed'] ) ) {
				$alt = ' alt="' . sprintf( __( 'Feed for all posts filed under %s' ), $cat_name ) . '"';
			} else {
				$alt   = ' alt="' . $args['feed'] . '"';
				$name  = $args['feed'];
				$link .= empty( $args['title'] ) ? '' : $args['title'];
			}
 
			$link .= '>';
 
			if ( empty( $args['feed_image'] ) ) {
				$link .= $name;
			} else {
				$link .= "<img src='" . esc_url( $args['feed_image'] ) . "'$alt" . ' />';
			}
			$link .= '</a>';
 
			if ( empty( $args['feed_image'] ) ) {
				$link .= ')';
			}
		}
 
		if ( ! empty( $args['show_count'] ) ) {
			$link .= ' (' . number_format_i18n( $category->count ) . ')';
		}
		if ( 'list' == $args['style'] ) {
			$output     .= "\t<li";
			$css_classes = array(
				'cat-item',
				'cat-item-' . $category->term_id,
			);
 
			if ( ! empty( $args['current_category'] ) ) {
				// 'current_category' can be an array, so we use `get_terms()`.
				$_current_terms = get_terms(
					$category->taxonomy,
					array(
						'include'    => $args['current_category'],
						'hide_empty' => false,
					)
				);
 
				foreach ( $_current_terms as $_current_term ) {
					if ( $category->term_id == $_current_term->term_id ) {
						$css_classes[] = 'current-cat';
					} elseif ( $category->term_id == $_current_term->parent ) {
						$css_classes[] = 'current-cat-parent';
					}
					while ( $_current_term->parent ) {
						if ( $category->term_id == $_current_term->parent ) {
							$css_classes[] = 'current-cat-ancestor';
							break;
						}
						$_current_term = get_term( $_current_term->parent, $category->taxonomy );
					}
				}
			}
 
			/**
			 * Filters the list of CSS classes to include with each category in the list.
			 *
			 * @since 4.2.0
			 *
			 * @see wp_list_categories()
			 *
			 * @param array  $css_classes An array of CSS classes to be applied to each list item.
			 * @param object $category    Category data object.
			 * @param int    $depth       Depth of page, used for padding.
			 * @param array  $args        An array of wp_list_categories() arguments.
			 */
			$css_classes = implode( ' ', apply_filters( 'category_css_class', $css_classes, $category, $depth, $args ) );
			$css_classes = $css_classes ? ' class="' . esc_attr( $css_classes ) . '"' : '';
 
			$output .= $css_classes;
			$output .= ">$link\n";
		} elseif ( isset( $args['separator'] ) ) {
			$output .= "\t$link" . $args['separator'] . "\n";
		} else {
			$output .= "\t$link<br />\n";
		}
	}

} 