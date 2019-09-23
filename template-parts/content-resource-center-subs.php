<?php
/**
 * Template part for displaying page content in page-resource-center-subs.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Lead_Pittsburgh
 */
?>

<?php

$pages 				= get_pages( 'child_of=' . get_the_ID() );
$nothing_to_show 	= ( ( count( $pages ) > 0 ) && 
						( 'page-resource-center' == get_page_template_slug() )
					  );

$page_ID 			= get_the_ID();

// Show resource listing only if the [ we're on the Resource Center page AND this 
// page doesn't have children ] statement returns false.

if ( false == $nothing_to_show ): ?>

	<div class="wrap--resource-page__main">
		
		<div class="resource-page__main container">
			
			<div class="flex-container">
				
				<div class="flex-item wrap--resource-categories">
					
					<?php
					$d_cbanner 			= get_field('d_cat_banner', 'option');					
					$cbanner_title 		= get_field('cat_banner_title', 'option');
					$cbanner_content 	= get_field('cat_banner_content', 'option');

					if ( $d_cbanner || ( NULL === $d_cbanner ) ) : ?>

						<div class="wrap--category-top-banner">
							
							<div class="category-top-banner">
								
								<?php
								if ( $cbanner_title && ( '' !== $cbanner_title ) ) 
									echo "<h3>{$cbanner_title}</h3>";

								if ( $cbanner_content && ( '' !== $cbanner_content ) ) 
									echo "<div>{$cbanner_content}</div>";
								?>

							</div>

						</div>

					<?php 
					endif;
					?>

					<ul class="resource-categories">
					
						<?php
						$cat_args = array(
							'taxonomy' 		=> 'resource_category',
							'order'         => 'asc',
							'hide_empty'    => 1,
							'title_li'		=> '', 
							'depth'			=> 4,
							'walker' 		=> new Lead_Category_Walker(),
						);

						wp_list_categories( $cat_args );
						?>

					</ul>

				</div>

				<div class="flex-item wrap--resource-listing">

					<?php
					$sticky_args = array( 
						'posts_per_page' 	=> -1, 
						'post_type' 		=> 'resource',
						'post_status' 		=> array( 'publish' ),
						'meta_key'			=> 'res_single_sticky',
						'meta_value'		=> true,						
					);

					$stickies_Arr 	= get_posts( $sticky_args );
					$sticky_ids_Arr = [];

					foreach ( $stickies_Arr as $post ) : setup_postdata( $post );

						$sticky_ids_Arr[] = $post->ID;
						?>
					
						<div class="resource-stickies">

							<ul>
								
								<?php
								/**
								 * Let's get the current resource's resource categories
								 * ----------------------------------------------------------------
								 */
								$terms 				= wp_get_post_terms( get_the_ID(), 'resource_category', array() );
								$term_names 		= [];
								$hideonpage_ids 	= [];

								/**
								 * Check if the `d_resource_on_page` array includes the 
								 * current resource id - meaning if the current resource
								 * really needs to be displayed in the listing.
								 *
								 * ----------------------------------------------------------------
								 */
								
								$resource_ID 		= get_the_ID();
								$show_on_pages_Arr 	= get_field('d_resource_on_page');

								// If the returned value is an integer, not an array ( resource 
								// is set to be displayed on one page only ), convert it to a 
								// one-item array.
								if ( !is_array($show_on_pages_Arr) )
									$show_on_pages_Arr = array_map( 'intval', explode(',', $show_on_pages_Arr) );

								$show_it 			= in_array( $page_ID, $show_on_pages_Arr );

								/**
								 * Now we're able to correctly decide if the resource is to 
								 * 		be listed or not.
								 * ----------------------------------------------------------------
								 */
								if ( $show_it ) : 

									/** 
									 * Even if the resource is set to be displayed on the
									 * current page, if all its resource categories are set to
									 * be hidden on the current page, the resource should not 
									 * be shown.
									 *
									 * +
									 *
									 * Now that we're querying the terms, let's use the same 
									 * query to fill the term classes array that'll be used in
									 * the Isotop configuration.
									 * ------------------------------------------------------------
									 */
									
									// Let's create an array to contain this current resource's
									// terms whose display control is set to be hidden on 
									// this current page
									$banned_ids = [];

									// Create the terms classes string
									// e.g. class="category-1 category-2"
									// we'll need the current resource's terms slugs, separated 
									// by a space.
									$term_slug_Arr = [];

									foreach ( $terms as $term ) :

										$term_slug_Arr[] = $term->slug;

										$hide_this_term_on_these_pages_Arr = get_field( 'hide_resource_category', 'resource_category_' . $term->term_id );

										// If the returned value is an integer, not an 
										// array ( term is set to be displayed on one page 
										// only ), convert it to a one-item array.
										if ( !is_array($hide_this_term_on_these_pages_Arr) )
											$hide_this_term_on_these_pages_Arr = array_map( 'intval', explode(',', $hide_this_term_on_these_pages_Arr) );										

										// If the term is banned on this page, add its id to the 
										// banned ids array 
										if ( in_array( $page_ID, $hide_this_term_on_these_pages_Arr ) ) :
											$banned_ids[] = $term->term_id;
										// otherwise add its id to the $term_names array so 
										// that we can use it later while implementing the 
										// term display in the resource top corner. 
										else :
											$term_names[] 	= $term->name;
										endif;

									endforeach;

									// If the two arrays have the same amount of items, it means
									// that the current resource's ALL TERMS are banned on this page, THUS the resource itself becomes banned.
									$show_it2 = ( count($terms) !== count($banned_ids) );
									
									if ( false == $show_it2 )
										return;

									// + some Isotope stuff...
									$term_class_Str = implode( ' ', $term_slug_Arr );
									?>

									<li class="<?php echo $term_class_Str; ?>">

										<div class="entry-resource rentry">
									
											<div class="rentry__termlist">
			
												<?php
												foreach ( $term_names as $name ) :

													/* Get the term 
													------------------------------- */			

													$term = get_term_by('name', $name, 'resource_category');

													/* Get term custom background color 
													----------------------------------- */
													
													// 2. Check if term has parent
													$has_parent = ( 0 == $term->parent ) ? false : true;
													$term_class = $has_parent ? 'has-parent' : 'no-has-parent';

													$bck_col 	= get_field( 'category_color', 'resource_category_' . $term->term_id );
													
													$col 		= $bck_col ? '#fff' : '#1450ff';
													$col_Str 	= 'color: ' . $col;

													$bck_col 	= $bck_col ? $bck_col : '#f0f0f1';
													$bck_col 	= $has_parent ? $bck_col : '#f0f0f1';
													$bck_Str 	= 'background-color: ' . $bck_col;

													$termlink 	= get_term_link($term->term_id);

													$termclass 	= ( 0 == $term->parent ) 
																	? 'top-term'
																	: 'no-top-term';

													/* Isotope stuff
													-------------------------------------------- */

													$term_slug = $term->slug;

													echo "<a data-filter='.{$term_slug}' href='{$termlink}' class='{$termclass} undeco rentry__term' style='{$col_Str}; {$bck_Str};'>{$name}</a>";
												
												endforeach;
												?>

											</div>
								
											<?php 
											$rc_titleurl = get_field('res_single_rc_titleurl');
											$rc_titleurl = (filter_var( 
																$rc_titleurl, 
																FILTER_VALIDATE_URL 
																) 
															) 
															? $rc_titleurl
															: get_permalink();


											echo '<h3 class="resource__title"><a href="' . $rc_titleurl . '">' . get_the_title() . '</a></h3>';
											echo '<div class="resource__content">'; 

											// the_content() CAN'T BE USED HERE! IF THE CONTENT 
											// IS CUT IN HALF WITH THE "READ MORE" SEPARATOR IN 
											// THE POST EDITOR, THE_CONTENT, IN A LOOP, SHOWS 
											// ONLY THE PART BEFORE THE "READ MORE"!!!
											echo apply_filters('the_content', $post->post_content);
											
											echo '</div>'; 
											?> 
										
										</div>

									</li>		

								<?php 
								endif; ?>						

							</ul>

						</div>

					<?php
					endforeach;
					
					wp_reset_postdata();
					?>

					<div class="resource-listing">
						
						<?php
						/**
						 * ========================================================================
						 * 1. Query all published resources
						 * ========================================================================
						 */
						$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

						$res_args 	= array(
							//'posts_per_page' 	=> 30,
							'posts_per_page' 	=> -1,							
							'post_type' 		=> 'resource',
							'post_status' 		=> array( 'publish' ),
							'post__not_in' 		=> $sticky_ids_Arr,
							//'paged'             => $paged,
						);

						$the_query = new WP_Query( $res_args );

						/**
						 * ========================================================================
						 * 2. Open the loop
						 * ========================================================================
						 */
						if ( $the_query->have_posts() ) :

							echo '<ul>';

							while ( $the_query->have_posts() ) :

								$the_query->the_post();

								/**
								 * ================================================================
								 * 3. We need to find out if the current resource has one of 
								 * 		the resource categories disabled on the current page 
								 * 		via ACF.
								 * ===============================================================
								 */

								/**
								 * Let's get the current resource's resource categories
								 * ----------------------------------------------------------------
								 */
								$terms 				= wp_get_post_terms( get_the_ID(), 'resource_category', array() );
								$term_names 		= [];
								$hideonpage_ids 	= [];

								/**
								 * Check if the `d_resource_on_page` array includes the 
								 * current resource id - meaning if the current resource
								 * really needs to be displayed in the listing.
								 *
								 * ----------------------------------------------------------------
								 */
								
								$resource_ID 		= get_the_ID();
								$show_on_pages_Arr 	= get_field('d_resource_on_page');

								// If the returned value is an integer, not an array ( resource 
								// is set to be displayed on one page only ), convert it to a 
								// one-item array.
								if ( !is_array($show_on_pages_Arr) )
									$show_on_pages_Arr = array_map( 'intval', explode(',', $show_on_pages_Arr) );

								$show_it 			= in_array( $page_ID, $show_on_pages_Arr );

								/**
								 * Now we're able to correctly decide if the resource is to 
								 * 		be listed or not.
								 * ----------------------------------------------------------------
								 */
								if ( $show_it ) : 

									/** 
									 * Even if the resource is set to be displayed on the
									 * current page, if all its resource categories are set to
									 * be hidden on the current page, the resource shouldnot 
									 * be shown.
									 *
									 * +
									 *
									 * Now that we're querying the terms, let's use the same 
									 * query to fill the term classes array that'll be used in
									 * the Isotop configuration.
									 * ------------------------------------------------------------
									 */
									
									// Let's create an array to contain this current resource's
									// terms whose display control is set to be hidden on 
									// this current page
									$banned_ids = [];

									// Create the terms classes string
									// e.g. class="category-1 category-2"
									// we'll need the current resource's terms slugs, separated 
									// by a space.
									$term_slug_Arr = [];

									foreach ( $terms as $term ) :

										$term_slug_Arr[] = $term->slug;

										$hide_this_term_on_these_pages_Arr = get_field( 'hide_resource_category', 'resource_category_' . $term->term_id );

										// If the returned value is an integer, not an 
										// array ( term is set to be displayed on one page 
										// only ), convert it to a one-item array.
										if ( !is_array($hide_this_term_on_these_pages_Arr) )
											$hide_this_term_on_these_pages_Arr = array_map( 'intval', explode(',', $hide_this_term_on_these_pages_Arr) );										

										// If the term is banned on this page, add its id to the 
										// banned ids array 
										if ( in_array( $page_ID, $hide_this_term_on_these_pages_Arr ) ) :
											$banned_ids[] = $term->term_id;
										// otherwise add its id to the $term_names array so 
										// that we can use it later while implementing the 
										// term display in the resource top corner. 
										else :
											$term_names[] 	= $term->name;
										endif;

									endforeach;

									// If the two arrays have the same amount of items, it means
									// that the current resource's ALL TERMS are banned on this page, THUS the resource itself becomes banned.
									$show_it2 = ( count($terms) !== count($banned_ids) );
									
									if ( false == $show_it2 )
										return;

									// + some Isotope stuff...
									$term_class_Str = implode( ' ', $term_slug_Arr );

									/* ============================================================
									Isotope stuff
									============================================================ */
									?>

									<li class="wrap--entry-resource <?php echo $term_class_Str; ?>">

										<div class="entry-resource rentry">
									
											<div class="rentry__termlist">
			
												<?php
												foreach ( $term_names as $name ) :

													/* Get the term 
													------------------------------- */			

													$term = get_term_by('name', $name, 'resource_category');

													/* Get term custom background color 
													----------------------------------- */
													
													// 2. Check if term has parent
													$has_parent = ( 0 == $term->parent ) ? false : true;
													$term_class = $has_parent ? 'has-parent' : 'no-has-parent';

													$bck_col 	= get_field( 'category_color', 'resource_category_' . $term->term_id );
													
													$col 		= $bck_col ? '#fff' : '#1450ff';
													$col_Str 	= 'color: ' . $col;

													$bck_col 	= $bck_col ? $bck_col : '#f0f0f1';
													$bck_col 	= $has_parent ? $bck_col : '#f0f0f1';
													$bck_Str 	= 'background-color: ' . $bck_col;

													$termlink 	= get_term_link($term->term_id);

													$termclass 	= ( 0 == $term->parent ) 
																	? 'top-term'
																	: 'no-top-term';

													/* Isotope stuff
													-------------------------------------------- */

													$term_slug = $term->slug;

													echo "<a data-filter='.{$term_slug}' href='{$termlink}' class='{$termclass} undeco rentry__term' style='{$col_Str}; {$bck_Str};'>{$name}</a>";
												
												endforeach;
												?>

											</div>
								
											<?php 
											$rc_titleurl = get_field('res_single_rc_titleurl');
											$rc_titleurl = (filter_var( 
																$rc_titleurl, 
																FILTER_VALIDATE_URL 
																) 
															) 
															? $rc_titleurl
															: get_permalink();


											echo '<h3 class="resource__title"><a href="' . $rc_titleurl . '">' . get_the_title() . '</a></h3>';
											echo '<div class="resource__content">'; 

											// the_content() CAN'T BE USED HERE! IF THE CONTENT 
											// IS CUT IN HALF WITH THE "READ MORE" SEPARATOR IN 
											// THE POST EDITOR, THE_CONTENT, IN A LOOP, SHOWS 
											// ONLY THE PART BEFORE THE "READ MORE"!!!
											echo apply_filters('the_content', $post->post_content);
											
											echo '</div>'; 
											?> 
										
										</div>

									</li>

								<?php 
								endif;
							
							endwhile;

							echo '</ul>';
							?>

							<?php 
							/* --------------------------------------------------------------------
							# Pagination (TEMP?)
							-------------------------------------------------------------------- */

							// Pagination - NOT RECOMMENDED, it doesn't work together with Isotope - it might be able to, but not within the current project timeframe.
							?>
							<!--<div class="wrap--resource-navigation">

								<div class="resource-navigation">
									
									<?php resource_pagination( $the_query ); ?>

								</div>

							</div>-->							

							<?php														
							/* Restore original Post Data */
							wp_reset_postdata();	

							/* --------------------------------------------------------------------
							# Resource Listing Bottom Note
							-------------------------------------------------------------------- */

							$d_note = get_field('d_rlbnote', 'option');
							$note 	= get_field('rlbnote_content', 'option');						
							if ( $d_note || ( NULL === $d_note ) ) :

								echo "<div class='wrap--bottom-note'><div class='bottom-note'>{$note}</div></div>";

							endif;														

						else :

							// no posts found
						
						endif;
						?>					

					</div>

				</div>			

			</div>

		</div>

	</div>

<?php endif;

if ( '' !== get_the_content() ) : ?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		
		<div class="entry-content"><?php the_content(); ?></div><!-- .entry-content -->

		<?php if ( get_edit_post_link() ) : ?>
			<footer class="entry-footer container">
				<?php
				edit_post_link(
					sprintf(
						wp_kses(
							/* translators: %s: Name of current post. Only visible to screen readers */
							__( 'Edit <span class="screen-reader-text">%s</span>', 'lead' ),
							array(
								'span' => array(
									'class' => array(),
								),
							)
						),
						get_the_title()
					),
					'<span class="edit-link">',
					'</span>'
				);
				?>
			</footer><!-- .entry-footer -->
		<?php endif; ?>

	</article><!-- #post-<?php the_ID(); ?> -->

<?php endif; ?>