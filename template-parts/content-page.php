<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Lead_Pittsburgh
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
	<div class="wrap--article-content container">

		<div class="article-content">

			<?php 
			$showtitle = get_field('page_showtitle');

			if ( $showtitle ) : ?>

				<header class="entry-header">
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				</header><!-- .entry-header -->

			<?php 
			endif; 

			lead_post_thumbnail(); ?>

			<div class="entry-content">
				<?php
				the_content();

				wp_link_pages( array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'lead' ),
					'after'  => '</div>',
				) );
				?>
			</div><!-- .entry-content -->

		</div>	

	</div>

	<?php if ( get_edit_post_link() ) : ?>
		<footer class="entry-footer">

			<div class="wrap--edit-btn container">

				<div class="edit-btn">

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

				</div>

			</div>
		
		</footer><!-- .entry-footer -->
	<?php endif; ?>

</article><!-- #post-<?php the_ID(); ?> -->
