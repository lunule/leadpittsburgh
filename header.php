<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Lead_Pittsburgh
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>

	<script type='text/javascript'>
	
		(function (d, t) {
			var bh = d.createElement(t), s = d.getElementsByTagName(t)[0];
			bh.type = 'text/javascript';
			bh.src = 'https://www.bugherd.com/sidebarv2.js?apikey=gpk6bvaiobniztgzokyywg';
			s.parentNode.insertBefore(bh, s);
		})(document, 'script');
	
	</script>	

	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-75143913-1"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());

	  gtag('config', 'UA-75143913-1');
	</script>

</head>

<body <?php body_class(); ?>>

<div id="page" class="site">

	<?php
	$banner 	= get_field('banner_content', 'option');
	$binit 		= get_field('banner_init', 'option');
	$dbanner 	= get_field('banner_display', 'option');

	if ( 
			is_front_page() 					&&
			( $banner && ( '' !== $banner ) ) 	&&
			!$dbanner
		) :
	?>

		<div class="wrap--lead-banner">
			
			<div class="lead-banner container">
				
				<div class="lead-banner__content"><?php echo $banner; ?></div>

				<a href="#"><i class="fas fa-times-circle"></i></a>

			</div>

		</div>

	<?php 
	endif; ?>

	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'lead' ); ?></a>

	<div class="hamburger hamburger--spin">
		<div class="hamburger-box">
			<div class="hamburger-inner"></div>
		</div>
	</div>					

	<nav id="mobile-navigation" class="mobile-navigation">

		<div class="menu-main-navigation-container">

			<?php
			wp_nav_menu( array(
				'theme_location' => 'menu-1',
				'menu_id'        => 'primary-menu',
				'container' 	 => '',
			) );
			?>

		</div>

	</nav><!-- #mobile-navigation -->

	<div class="wrap--site-head">

		<div class="site-head container">

			<header id="masthead" class="site-header flex-container">

				<div class="site-branding flex-item">

					<?php
					the_custom_logo();
					?>

				</div><!-- .site-branding -->

				<nav id="site-navigation" class="main-navigation flex-item">

					<div class="menu-main-navigation-container">

						<?php
						wp_nav_menu( array(
							'theme_location' 	=> 'menu-1',
							'menu_id'        	=> 'primary-menu',
							'container' 	 	=> '',
							'depth' 			=> 1,
						) );
						?>

					</div>

				</nav><!-- #site-navigation -->
			</header><!-- #masthead -->			

		</div>

		<?php
		global $post;

		$hide_search = ( NULL !== $post )
							? get_field( 'page_hidesearch', $post->ID )
							: true;

		if ( ( 'page' == get_post_type() ) && !is_front_page() && !$hide_search ) : ?>

			<div class="wrap--secnav">

				<div class="container flex-container">

					<?php
					// If on a page, return list of sub-pages
					$post_type 	= 'page';
					// - 	If the current page is a subpage/has a parent, we query 
					// 		the children by parent page id.
					// - 	If the current page is a top-level page, we query the 
					// 		children by this page's ID. 
					$child_of 	= ( $post->post_parent ) ? wp_get_post_parent_id( get_the_ID() ) : get_the_ID();

					$args = array( 
						'post_type' 		=> $post_type, 
						'post_status' 		=> 'publish', 
						'child_of' 			=> $child_of,
						'depth' 			=> 1,
						'title_li' 			=> '',
						'echo' 				=> false,
						'sort_order' 		=> 'asc',
						'sort_column' 		=> 'post_date',
					);

					$pages = wp_list_pages( $args );

					if ( !empty( $pages ) ) : ?>

						<ul class="secnav secnav--<?php echo $post_type; ?> flex-item flex-container">
				
							<?php echo $pages; ?>

						</ul>

					<?php 
					endif; 
					?>
							
					<div class="leadsearch flex-item"><?php get_search_form(); ?></div>

				</div>

			</div>

		<?php
		endif; ?>

	</div>

	<div id="content" class="site-content">
