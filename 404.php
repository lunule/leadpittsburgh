<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Lead_Pittsburgh
 */

get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

			<div class="wrap--notfound-content container">

				<div class="notfound-content flex-container">

					<div class="notfound--left flex-item">

						<section class="error-404 not-found">
							<header class="page-header">
								<h1 class="page-title"><?php esc_html_e( 'Our Apologies.', 'lead' ); ?></h1>
							</header><!-- .page-header -->

							<div class="page-content">
								<p>We're sorry! We can't find the page you've requested.<br />The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.</p>

								<p>Please use the navigation above or go to LEAD Pittsburgh's <a href="<?php echo get_site_url(); ?>">homepage</a>. You may also type in a key word in the search box below.</p>

								<p>If you need further assistance, please contact us at <a href="mailto:info@leadpittsburgh.org">info@leadpittsburgh.org</a>.</p>

								<p>Thank you for your interest in LEAD Pittsburgh.</p>

								<?php
								get_search_form();
								?>

							</div><!-- .page-content -->
						</section><!-- .error-404 -->

				</div>

			</div>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
