<?php
/* ================================================================================================
# DEFINE CPT - Resource
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
			'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions', 'page-attributes', 'custom-fields', 'excerpt' ),
			'taxonomies'            => array(),
			'hierarchical'          => true,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 20,
			'menu_icon'             => 'dashicons-portfolio',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => false,
			'exclude_from_search'   => true,
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

		/* ========================================================================================
		Don't generate an element if the category display is disabled for the current page via ACF.
		======================================================================================== */
		
		$hideonpage_Arr = get_field( 'hide_resource_category', 'resource_category_' . $category->term_id );

		// Set up the default value of the variable storing the current page's ID
		$page_ID = get_the_ID();
		
		// If the field returns a value
		if ( !empty( $hideonpage_Arr ) ) :

			if ( in_array( $page_ID, $hideonpage_Arr ) )
				return;

		endif;		

		/* ========================================================================================
		Check if term has parent
		======================================================================================== */

		$has_parent = ( 0 == $category->parent ) ? false : true;
		$term_class = $has_parent ? 'has-parent' : 'no-has-parent';
		$bck_col 	= get_field( 'category_color', 'resource_category_' . $category->term_id );
		$bck_col 	= $bck_col ? $bck_col : 'transparent';
		$bck_col 	= $has_parent ? $bck_col : 'transparent';
		$bck_Str 	= 'background-color: ' . $bck_col;

		/* ========================================================================================
		Check if term has children
		======================================================================================== */

		$children 		= get_term_children( $category->term_id, 'resource_category');
		$new_children 	= [];

		foreach ( $children as $child ) :

			$child_hideonpage_Arr = get_field( 'hide_resource_category', 'resource_category_' . $child );	
			
			// If the field returns a value
			if ( !empty( $child_hideonpage_Arr ) ) :

				if ( in_array( $page_ID, $child_hideonpage_Arr ) ) 
					array_push( $new_children, $child);

			endif;					

		endforeach;

		sort( $children );
		sort( $new_children );

		$children_class = ( empty($children) || ( $children == $new_children ) ) ? 'no-has-children' : 'has-children';

		/* ========================================================================================
		Isotope stuff
		======================================================================================== */

		// Create the data-filter value string
		// e.g. data-filter=".category-1, .category-2"
		// we'll need the current term's and its children terms' slugs, prepended with 
		// a dot (class name!!), and separated by a comma and a space.
		$this_term_slug 	= '.' . $category->slug;
		$children_id_Arr 	= get_term_children( $category->term_id, 'resource_category' ); 
		$children_slug_Arr 	= [];

		foreach ( $children_id_Arr as $term_ID ) :

			$child_Obj 				= get_term_by( 'id', $term_ID, 'resource_category' );
			$children_slug_Arr[] 	= '.' . $child_Obj->slug;

		endforeach;

		$all_terms_eve 		= array_push( $children_slug_Arr, $this_term_slug);
		$data_filter_Str 	= implode( ', ', $children_slug_Arr );

		/* ========================================================================================
		EOF chex
		======================================================================================== */

		// Don't generate an element if the category name is empty.
		if ( '' === $cat_name ) {
			return;
		}
 
 		$term_link = esc_url( get_term_link( $category ) );
		
		$link = "<a data-filter='{$data_filter_Str}' style='{$bck_Str}' class='undeco {$term_class}' href='{$term_link}'";
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

		if ( !empty($children) ) 
        	$link .= "<span class='flex-container slide-toggle'><span class='flex-item'></span></span>";

		$link .= $cat_name . '</a>';
 
		if ( ! empty( $args['feed_image'] ) || ! empty( $args['feed'] ) ) {
			$link .= ' ';
 
			if ( empty( $args['feed_image'] ) ) {
				$link .= '(';
			}
 
			$term_feed_link = esc_url( get_term_feed_link( $category->term_id, $category->taxonomy, $args['feed_type'] ) );

			$link .= "<a data-filter='{$data_filter_Str}' style='{$bck_Str}' class='undeco {$term_class}' href='{$term_feed_link}'";
 
			if ( empty( $args['feed'] ) ) {
				$alt = ' alt="' . sprintf( __( 'Feed for all posts filed under %s' ), $cat_name ) . '"';
			} else {
				$alt   = ' alt="' . $args['feed'] . '"';
				$name  = $args['feed'];
				$link .= empty( $args['title'] ) ? '' : $args['title'];
			}
 
			$link .= '>';

			if ( !empty($children) ) 
    	    	$link .= "<span class='flex-container slide-toggle'><span class='flex-item'></span></span>";			
 
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
				$children_class,
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

/* ================================================================================================
# Resource pagination
================================================================================================ */

function resource_pagination( $custom_query ) {

    $total_pages = $custom_query->max_num_pages;
    $big = 999999999; // need an unlikely integer

    if ($total_pages > 1) :

        $current_page = max(1, get_query_var('paged'));

        echo paginate_links(array(
            'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
            'format' => '?paged=%#%',
            'current' => $current_page,
            'total' => $total_pages,
        ));
    
    endif;
}

