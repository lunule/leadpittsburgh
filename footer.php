<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Lead_Pittsburgh
 */

?>

	</div><!-- #content -->

	<div class="wrap--site-foot">

		<div class="site-foot">

			<footer id="colophon" class="site-footer container flex-container">

				<?php
				for ( $i = 0; $i <= 5; $i++ ) :

					$callmesam = 'footbar-' . $i; 

					if ( is_active_sidebar( $callmesam ) ) : ?>

						<aside class="widget-area flex-item widget-area--<?php echo $i; ?>">
							<?php dynamic_sidebar( $callmesam ); ?>
						</aside>

					<?php 
					endif;

				endfor;
				?>

			</footer><!-- #colophon -->

		</div>

	</div>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
