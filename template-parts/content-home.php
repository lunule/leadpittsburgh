<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Lead_Pittsburgh
 */

/* ================================================================================================
# Hero Slider
================================================================================================ */

$btn_label 		= get_field('home_hero_btn_label');
$btn_linkto 	= get_field('home_hero_btn_linkto'); 

if ( have_rows('home_hero_slides') ) :

	echo '<div class="wrap--hero-slider">'; ?>

		<?php 	
		if ( ( $btn_label 	&& ( '' !== $btn_label ) ) &&
		  	 ( $btn_linkto 	&& ( '' !== esc_url( $btn_linkto ) ) )
			) : ?>

			<div class="slider-overlay container">
				
				<?php echo "<a href='{$btn_linkto}' class='hero-slider__btn undeco flex-item'>{$btn_label}</a>"; ?>

			</div>

		<?php endif; ?>

		<div class="hero-slider">

			<?php
			while ( have_rows('home_hero_slides') ) : the_row(); 

				$image 		= get_sub_field('img');
				$url 		= $image['url'];			

				$tagline 	= get_sub_field('tagline');
				?>

				<div class="hslide">

					<?php
					if ( $url && ( '' !== esc_url( $url ) ) ) 
						echo "<div class='hslide__bck' style='background: transparent url({$url}) center center/cover no-repeat;'></div>"; 
					if ( $tagline && ( '' !== $tagline ) ) 
						echo "<div class='hslide__tagline container'><h2>{$tagline}</h2></div>"; 
					?>				

				</div>

			<?php 
			endwhile; ?>

		</div>

	<?php 
	echo '</div>';

endif; 
?>

<?php 
/* ================================================================================================
# Aims
================================================================================================ */

$aims_title 	= get_field('home_aims_title' );
$has_title 		= isset( $aims_title ) && ( '' !== $aims_title );

$aims_content 	= get_field('home_aims_content' );
$has_content 	= isset( $aims_content ) && ( '' !== $aims_content );

if ( $has_title || $has_content ) : ?>

	<div class="wrap--home-aims">
		
		<div class="home-aims container">
			
			<?php
			if ( $has_title ) echo "<h2 class='home-aims__title'>{$aims_title}</h2>";
			if ( $has_content ) echo "<div class='home-aims__content'>{$aims_content}</div>";
			?>

		</div>

	</div>

<?php 
endif;

/* ================================================================================================
# Counters
================================================================================================ */

$counters_title = get_field('home_counters_title');
$has_title 		= isset( $counters_title ) && ( '' !== $counters_title );

$bck_Obj 		= get_field('home_counters_bck');
$bck 			= $bck_Obj['url'];

if ( $has_title || have_rows('home_counters_counters') ) :
?>

	<div class="wrap--home-counters" style="background: #1450ff url('<?php echo $bck; ?>') center center/cover no-repeat;">
		
		<div class="home-counters container">
		
		<?php 
		if ( $has_title ) echo "<h2 class='home-counters__title'>{$counters_title}</h2>";

		if ( have_rows('home_counters_counters') ) :

			echo '<div class="flex-container">';

			while ( have_rows('home_counters_counters') ) :	the_row();

				$target 		= get_sub_field('target');
				$has_target 	= isset( $target ) && is_numeric( $target );

				$target_u 		= get_sub_field('target_unit');
				$unit 			= false;
				switch ( $target_u ) :

					case 'none' : 	$unit = ''; break;
					default : 		$unit = '%'; break;
				
				endswitch;

				$desc 			= get_sub_field('desc');
				$note 			= get_sub_field('note'); 
				?>

				<div class="flex-item">
					
					<?php
					if ( $has_target && isset( $target_u ) ) 
						echo "<div class='home-counters__target'><span class='wrap--odometer'><span class='odometer' data-target='{$target}'>0</span></span>{$unit}</div>";

					if ( $desc && ( '' !== $desc ) ) 
						echo "<div class='home-counters__desc'>{$desc}</div>";

					if ( $note && ( '' !== $note ) ) 
						echo "<div class='home-counters__note'>{$note}</div>";					
					?>

				</div>

			<?php
			endwhile;

			echo '</div>';

		endif;
		?>

		</div>

	</div>

<?php
endif;

/* ================================================================================================
# Featured Resources
================================================================================================ */

$title 			= get_field('home_fres_title');
$has_title 		= isset( $title ) && ( '' !== $title );

$resources 		= get_field('home_fres_select');
$btn_label 		= get_field('home_fres_btn_label');
$btn_url 		= get_field('home_fres_btn_url');

$has_btn_label 	= ( $btn_label && ( '' !== $btn_label ) );
$has_btn_url 	= ( $btn_url && ( '' !== esc_url( $btn_url ) ) );

if ( $resources ) : ?>

	<div class="wrap--home-fresources">
		
		<div class="home-fresources container">

			<?php if ( $has_title ) echo "<h2 class='home-fresources__title'>{$title}</h2>"; ?>

			<div class="wrap--resource-blocks">

				<ul class="resource-blocks flex-container">

				<?php
			    foreach( $resources as $post): // variable must be called $post (IMPORTANT)

			        setup_postdata( $post );
			    						
					/**
					 * Let's get the current resource's resource categories
					 * ----------------------------------------------------------------
					 */							
					$terms 				= wp_get_post_terms( get_the_ID(), 'resource_category', array() );
					$term_names 		= [];

					foreach ( $terms as $term ) 
						$term_names[] 	= $term->name;	
					?>

					<li class="wrap--entry-resource flex-item">

						<div class="entry-resource rentry">
					
							<div class="rentry__termlist">

								<?php
								/* Why do we need indexing?
									Because we're building a breadcrumb trail. We only need the first term, that's why we break the foreach once $i > 0.
									With the first term we get this term's ancestors then etc., see below notes.

									If we weren't use indexing and foreach-break, the breadcrumb structure would break each time a resource has more than one resource category.
								*/ 
								$i = 0;

								foreach ( $term_names as $name ) :

									/* Get the term 
									------------------------------- */			
									$term = get_term_by('name', $name, 'resource_category');

									/* Get its ancestors 
									------------------------------- */
									$ancestory 		= [];
									$ancestors_Arr 	= get_ancestors( $term->term_id, 'resource_category' );
									$ancestors_Arr 	= array_reverse($ancestors_Arr);
									$has_ancestors 	= empty($ancestors_Arr) 
														? false
														: true; 
									/**
									 * Set the below variable value to TRUE if multiple 
									 * resource category display should be implemented.
									 */
									$need_all_terms = FALSE;

									if ( $has_ancestors && $need_all_terms ) :

										foreach ( $ancestors_Arr as $parent_term ) : 

											$anterm = get_term_by('id', $parent_term, 'resource_category');

											// Check if term has parent
											$has_parent = ( 0 == $anterm->parent ) ? false : true;
											$term_class = $has_parent ? 'has-parent' : 'no-has-parent';
											$bck_col 	= get_field( 'category_color', 'resource_category_' . $anterm->term_id );
											
											$col 		= $bck_col ? '#fff' : '#1450ff';
											$col_Str 	= 'color: ' . $col;

											$bck_col 	= $bck_col ? $bck_col : '#f0f0f1';
											$bck_col 	= $has_parent ? $bck_col : '#f0f0f1';
											$bck_Str 	= 'background-color: ' . $bck_col;

											$ancname 		= $anterm->name;
											$anclink 		= get_term_link($anterm->term_id);
											$ancclass 		= ( 0 == $anterm->parent ) 
													? 'top-ancestor'
													: 'no-top-ancestor';

											echo "<a href='{$anclink}' class='{$ancclass} undeco rentry__term-ancestor' style='{$col_Str}; {$bck_Str};'>{$ancname}</a>";

										endforeach;

									endif;

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

									echo "<span href='{$termlink}' class='{$termclass} rentry__term' style='{$col_Str}; {$bck_Str};'>{$name}</span>";
								
									// We only need the first term.
									$i++;
									if( $i > 0 ) break;

								endforeach;
								?>

							</div>
				
							<?php 
							$rtitle 			= get_the_title();
							$rurl 				= get_permalink();   

							$custom_targeturl 	= get_field('res_single_targeturl');

							if ( filter_var( $custom_targeturl, FILTER_VALIDATE_URL ) )
								$rurl = $custom_targeturl;

							$pdf 				= get_field('res_single_pdf');
							$has_pdf 			= ( $pdf && ( '' !== esc_url( $pdf ) ) );

							$pdf_Str 			= __('Download PDF', 'lead');
							$rm_Str 			= __('Read More', 'lead');

							echo "<h3 class='resource__title'><a class='undeco' href='{$rurl}'>{$rtitle}</a></h3>";
							echo '<div class="resource__content">'; 
							// the_excerpt();
							echo lead_custom_excerpt( get_the_content(), 65, get_permalink(), '' );
							echo '</div>'; 

							echo '<div class="resource__btn">';
							if ( $has_pdf ) :

								echo "<a href='{$pdf}' class='undeco has-pdf' target='_blank'>{$pdf_Str}</a>";

							else :

								echo "<a href='{$rurl}' class='undeco no-has-pdf'>{$rm_Str}</a>";

							endif;
							echo '</div>';
							?> 
						
						</div>

					</li>					
    
					<?php														
					/* Restore original Post Data */
					wp_reset_postdata();	
    
			    endforeach; ?>

				</ul>
		    
		    </div>

		    <?php 
		    wp_reset_postdata();

			if ( $has_btn_label && $has_btn_url ) : ?>

				<div class="wrap--more-resources">

					<div class="more-resources">
						
						<?php echo "<a href='$btn_url' class='undeco'>{$btn_label}</a>"; ?>

					</div>

				</div>

			<?php endif; ?>

		</div>

	</div>

<?php 
endif; 

/* ================================================================================================
# Featured Project
================================================================================================ */

$title 			= get_field('home_fproject_title');
$has_title 		= isset( $title ) && ( '' !== $title );

$cgroup 		= get_field('home_fproject_cgroup');
$content 		= $cgroup['content'];
$has_content 	= isset( $content ) && ( '' !== $content );

if ( $has_title && $has_content ) : ?>

	<div class="wrap--home-fproject">
		
		<div class="home-fproject container">
			
			<?php if ( $has_title ) echo "<h2 class='home-about__title'>{$title}</h2>"; ?>

			<div class="project-blocks flex-container">
				
				<?php 
				$bck 			= $cgroup['bck']['url'];
				
				$logo_Obj 		= $cgroup['logo'];
				$logo_size 		= 'lead-fproject-logo';
				$logo_alt 		= $logo_Obj['caption'];
				$logo_url 		= $logo_Obj['sizes'][$logo_size];
				$logo_width		= $logo_Obj['sizes'][$logo_size . '-width'];
				$logo_height	= $logo_Obj['sizes'][$logo_size . '-height'];	

				$desc 			= $cgroup['content'];
				$btn_label 		= $cgroup['btn_label'];
				$btn_url 		= $cgroup['btn_url'];											
				?>

				<div class="project-block project-block--main flex-item">
					
					<div class="project__bck" style="background: transparent url('<?php echo $bck; ?>') right center/contain no-repeat;"></div>

					<?php
					// Project logo ---------------------------------------------------------------
					if ( $logo_url ) :

						echo '<div class="project__logo">';

							if ( $btn_url ) echo "<a class='undeco' href='{$btn_url}' target='blank'>";
							
							echo "<img src='{$logo_url}' width='{$logo_width}' height='{$logo_height}' alt='{$logo_alt}' />";
							
							if ( $btn_url ) echo "</a>";

						echo '</div>';

					endif;

					// Project description --------------------------------------------------------
					if ( $desc && ( '' !== $desc ) ) : 

						echo "<div class='project__desc'>{$desc}</div>";

					endif;

					// Project button -------------------------------------------------------------
					if ( $btn_url && 
						 ( $btn_label && ( '' !== $btn_label ) ) 
						) : 

						echo "<div class='project__btn wrap--inblock'><a class='undeco inblock' href='{$btn_url}' target='_blank'>{$btn_label}</a></div>";

					endif;					
					?>

				</div>

				<?php 
				if ( have_rows('home_fproject_vids') ) :

					global $wp_embed;
					$lo = 1;

					while ( have_rows('home_fproject_vids') ) : the_row();

						$vid_url 			= get_sub_field('url');

						$vid_embed_search 	= '/youtube\.com\/watch\?v=([a-zA-Z0-9]+)/smi';
						$vid_embed_replace 	= "youtube.com/embed/$1";
						$vid_embed 			= preg_replace( $vid_embed_search, $vid_embed_replace, $vid_url );
						$vid_title 			= get_sub_field('title');						
						$vid_btn_label 		= get_sub_field('btn_label');

						/* The modulo operator (% in PHP) can be used to calculate the remainder of the value divided by 2. This gives a value of 0 for even numbers and a value of 1 for odd numbers. This can be used in an if statement AS 0 WILL EQUATE TO FALSE AND 1 WILL EQUATE TO TRUE. 
						This means that IF $lo%2 IS TRUE, IT MEANS $lo IS AN ODD NUMBER - NOT AN EVEN NUMBER AS YOU WOULD NORMALLY EXPECT!!! */
						$lo_class 		= ( $lo%2 ) ? 'odd' : 'even'; 

						echo "<div class='project-block project-block--video flex-item {$lo_class}'>";

							// Video embed --------------------------------------------------------
							if ( $vid_url ) :
								echo '<div class="project__video responsive-container">'; 
								echo $wp_embed->run_shortcode( '[embed]' . $vid_url . '[/embed]' );
								echo '</div>';
							endif;

							// Video title --------------------------------------------------------
							if ( $vid_title )
								echo "<h3 class='project__video-title'>{$vid_title}</h3>"; 

							// Video button -------------------------------------------------------
							if ( $vid_url && 
								 ( $vid_btn_label && ( '' !== $vid_btn_label ) ) 
								) : 

								echo "<div class='project__video-btn wrap--inblock'><a class='inblock undeco' href='{$vid_embed}' data-rel='lightcase'>{$vid_btn_label}</a></div>";

							endif;					

						echo '</div>';

						$lo++;

					endwhile;

				endif;
				?>

			</div>

		</div>

	</div>

<?php 
endif;

/* ================================================================================================
# About Lead Pittsburgh
================================================================================================ */

$about_title 	= get_field('home_about_title' );
$has_title 		= isset( $about_title ) && ( '' !== $about_title );

$about_content 	= get_field('home_about_content' );
$has_content 	= isset( $about_content ) && ( '' !== $about_content );

if ( $has_title || $has_content ) : ?>

	<div class="wrap--home-about">
		
		<div class="home-about container">
			
			<?php
			if ( $has_title ) echo "<h2 class='home-about__title'>{$about_title}</h2>";
			if ( $has_content ) echo "<div class='home-about__content'>{$about_content}</div>";
			?>

		</div>

	</div>

<?php 
endif;

/* ================================================================================================
# Default Content
================================================================================================ */
?>

<?php if ( '' !== get_the_content() ) : ?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

		<div class="entry-content">
			<?php
			the_content();

			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'lead' ),
				'after'  => '</div>',
			) );
			?>
		</div><!-- .entry-content -->

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