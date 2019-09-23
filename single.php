<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Lead_Pittsburgh
 */

get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

			<div class="wrap--single-content container">

				<div class="single-content flex-container">

					<div class="single--left single-main flex-item">

						<?php
						while ( have_posts() ) :
							the_post();

							get_template_part( 'template-parts/content', get_post_type() );

							the_post_navigation( array(
					            'prev_text'                  => '<span class="pn-prev"><< ' . __( 'Previous article' ) . '</span>%title',
					            'next_text'                  => '<span class="pn-prev">' . __( 'Next article' ) . ' >></span>%title',
							) );

						endwhile; // End of the loop.
						?>

					</div>

					<div class="single--right single-sidebar flex-item">
						
						<?php get_sidebar(); ?>

					</div>					

				</div>

			</div>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
