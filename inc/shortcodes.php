<?php
/**
 * Output the address specified on the LEAD Theme Settings options panel's Contact tab
 * 
 * @param  [type] $atts 	shortcode attributes array
 * @return [type]       	shortcode output
 */

/**
 * Helper function - Unnamed (aka No-Value) WordPress shortcode attributes
 *
 * @see https://richjenks.com/unnamed-wordpress-shortcode-attributes/
 * 
 * @param  [type]  $flag The queried attrbiute
 * @param  [type]  $atts The attributes array
 * @return boolean       true if flag exists ( meaning the no-value 
 *                       attribute is specified )
 */
function is_flag( $flag, $atts ) {

	if ( $atts ) :

		foreach ( $atts as $key => $value )
			if ( $value === $flag && is_int( $key ) ) return true;
	
	endif;

	return false;

}

/**
 * Output verious LEAD contact data
 * @param  [type] $atts    [description]
 * @param  [type] $content [description]
 * @return [type]          [description]
 */
function lead_sc_output_contact( $atts, $content = null ) {

	$a = shortcode_atts( array(
		'address-title'	=> __('Address', 'lead'),
		'phone-title'	=> __('Call', 'lead'),
		'email-title'	=> __('Write', 'lead'),
		'social-title'	=> __('Follow us on:', 'lead'),
		'social-icon' 	=> 'light',
		'exclude' 		=> '',
	), $atts );

	// implement shortcode attribute with no-value,
	// see helper function is_flag() above.
	$hide_titles	= is_flag( 'hide-titles', $atts ) ? true : false;	
	$hide_address 	= is_flag( 'hide-address', $atts ) ? true : false;
	$hide_phone 	= is_flag( 'hide-phone', $atts ) ? true : false;
	$hide_email 	= is_flag( 'hide-email', $atts ) ? true : false;
	$hide_social 	= is_flag( 'hide-social', $atts ) ? true : false;
	$custom_labels  = is_flag( 'custom-labels', $atts ) ? true : false;

	$excludes 		= explode(",", $a['exclude'] );

	$has_acf 			= class_exists('ACF');

	$address 			= get_field('opt_contact_address', 'option');
	$has_address 		= ( $address && ( '' !== $address ) && ( false == $hide_address ) );

	$phone1 			= get_field('opt_contact_phone1', 'option');
	$has_phone1 		= ( $phone1 && ( '' !== $phone1 ) && ( false == $hide_phone ) );

	$phone2 			= get_field('opt_contact_phone2', 'option');
	$has_phone2 		= ( $phone2 && ( '' !== $phone2 ) && ( false == $hide_phone ) );

	$email1 			= get_field('opt_contact_email1', 'option');
	$has_email1 		= ( $email1 && ( '' !== $email1 ) && ( false == $hide_email ) );

	$email2 			= get_field('opt_contact_email2', 'option');
	$has_email2 		= ( $email1 && ( '' !== $email2 ) && ( false == $hide_email ) );

	$has_social 		= ( have_rows( 'opt_contact_social', 'option' ) && ( false == $hide_social ) );

	if ( $has_acf ) :

		ob_start(); ?>

			<div class="wrap--lead-sc-contact">

				<div class="lead-sc-contact">

					<?php
					/* Address 
					---------- */
					if ( $has_address ) : ?>			

						<div class="lead-sc-contact__address">

							<?php
							if ( false == $hide_titles ) 
								echo "<h4>{$a['address-title']}</h4>";

							echo "<address>{$address}</address>";
							?>

						</div>

					<?php
					endif; ?>

					<?php
					/* Phone 
					-------- */
					if ( $has_phone1 || $has_phone2 ) : ?>			

						<div class="lead-sc-contact__phone">

							<?php							
							if ( false == $hide_titles ) 
								echo "<h4>{$a['phone-title']}</h4>";
							?>

							<ul>

								<?php
								if ( $has_phone1 ) echo "<li>{$phone1}</li>";
								if ( $has_phone2 ) echo "<li>{$phone2}</li>";
								?>

							</ul>

						</div>

					<?php
					endif; ?>

					<?php
					/* Email 
					-------- */					
					if ( $has_email1 || $has_email2 ) : ?>			

						<div class="lead-sc-contact__email">

							<?php							
							if ( false == $hide_titles ) 
								echo "<h4>{$a['email-title']}</h4>";
							?>

							<ul>

								<?php
								if ( $has_email1 ) echo "<li><a class='undeco' href='mailto:{$email1}'>{$email1}</a></li>";
								if ( $has_email2 ) echo "<li><a class='undeco' href='mailto:{$email2}'>{$email2}</a></li>";
								?>

							</ul>

						</div>

					<?php
					endif; ?>
					
					<?php
					/* Social 
					--------- */					
					if ( have_rows( 'opt_contact_social', 'option' ) && $has_social ) : 			
						echo '<div class="lead-sc-contact__social wrap--social">';

						if ( false == $hide_titles ) 
							echo "<h4>{$a['social-title']}</h4>";

						echo '<ul>';

						while ( have_rows( 'opt_contact_social', 'option' ) ) : the_row();

							$name 			= get_sub_field('name');

							$clabel 		= get_sub_field('clabel');
							$has_clabel 	= ( $clabel && ( '' !== $clabel ) );

							$name 			= ( ( true == $custom_labels ) && $has_clabel ) 
												? $clabel 
												: $name;

							$name_lc 		= strtolower( $name );
							$url 			= get_sub_field('url');
							
							$icon 			= ( 'light' == $a['social-icon'] ) 
													? get_sub_field('icon')
													: get_sub_field('icon_dark');

							$icon_url 		= $icon['url'];							

							if ( !in_array( get_row_index(), $excludes ) ) 
								echo "<li class='lead-social--{$name_lc}'><span class='icon-holder' style='background: transparent url({$icon_url}) center center/contain no-repeat;'></span><a href='{$url}' target='_blank' class='undeco'>{$name}</a></li>";  

						endwhile;

						echo '</ul></div>';

					endif; ?>

				</div>

			</div>
		
		<?php
		$output = ob_get_clean();

	else :

		$output = __('Please install/activate the Advanced Custom Fields Pro plugin', 'lead');

	endif;

	return $output;

}
add_shortcode( 'lead-contact', 'lead_sc_output_contact' );

/**
 * Output the LEAD address
 * @param  [type] $atts [description]
 * @return [type]       [description]
 */
function lead_sc_output_address( $atts ) {

	$a = shortcode_atts( array(
		//
	), $atts );

	$output = '<div class="wrap--address"><address>';

	if ( class_exists('ACF') ) : $output .= get_field('opt_contact_address', 'option'); 
	else : $output .= __('Please install/activate the Advanced Custom Fields Pro plugin', 'lead');
	endif;

	$output .= '</address></div>';

	return $output;

}
add_shortcode( 'lead-address', 'lead_sc_output_address' );

/**
 * Output the MailChimp subscription form
 * 
 * @param  [type] $atts 	shortcode attributes array
 * @return [type]       	shortcode output
 */
function lead_sc_output_mailchimp_form( $atts ) {

	$a = shortcode_atts( array(
		//'foo' => 'something',
		//'bar' => 'something else',
	), $atts );

	ob_start(); ?>

	<div class="wrap--subscribe">

		<!-- Begin Mailchimp Signup Form -->
		<div id="mc_embed_signup">
			
			<form action="https://leadpittsburgh.us1.list-manage.com/subscribe/post?u=ebb9742381a75f1c54b4b50d2&amp;id=67113f62c6" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
		    
		    	<div id="mc_embed_signup_scroll">
			
					<div class="mc-field-group">
						<input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL" placeholder="<?php _e('Your favorite email', 'lead'); ?>">
					</div>

					<div id="mce-responses" class="clear">
						<div class="response" id="mce-error-response" style="display:none"></div>
						<div class="response" id="mce-success-response" style="display:none"></div>
					</div>    

					<!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
		 			<div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_ebb9742381a75f1c54b4b50d2_67113f62c6" tabindex="-1" value=""></div>
		    		
		    		<div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
		    	
		    	</div>
			
			</form>

		</div>
		<!--End mc_embed_signup-->

	</div>

	<?php
	return ob_get_clean();

}
add_shortcode( 'lead-mailchimp', 'lead_sc_output_mailchimp_form' );

/**
 * Output the social network contact urls specified on the LEAD Theme Settings options 
 * panel's Contact tab
 * 
 * @param  [type] $atts 	shortcode attributes array
 * @return [type]       	shortcode output
 */
function lead_sc_output_social( $atts ) {

	$a = shortcode_atts( array(
		//'foo' => 'something',
		//'bar' => 'something else',
	), $atts );

	$output = '<div class="wrap--social">';

	if ( class_exists('ACF') ) : 

		if ( have_rows( 'opt_contact_social', 'option' ) ) : 			

			$output .= '<ul>';

			while ( have_rows( 'opt_contact_social', 'option' ) ) : the_row();

				$name 		= get_sub_field('name');
				$name_lc 	= strtolower( $name );
				$url 		= get_sub_field('url');
				$icon 		= get_sub_field('icon');
				$icon_url 	= $icon['url'];

				$output .= "<li class='lead-social--{$name_lc}'><span class='icon-holder' style='background: transparent url({$icon_url}) center center/contain no-repeat;'></span><a href='{$url}' target='_blank' class='undeco'>{$name}</a></li>";  

			endwhile;

			$output .= '</ul>';

		endif;
	
	else : 

		$output .= __('Please install/activate the Advanced Custom Fields Pro plugin', 'lead');
	
	endif;

	$output .= '</div>';

	return $output;

}
add_shortcode( 'lead-social', 'lead_sc_output_social' );

/**
 * Output the quicklink url specified on the LEAD Theme Settings options panel's Contact tab
 * 
 * @param  [type] $atts 	shortcode attributes array
 * @return [type]       	shortcode output
 */
function lead_sc_output_quickhelp_url( $atts ) {

	$a = shortcode_atts( array(
		//'foo' => 'something',
		//'bar' => 'something else',
	), $atts );

	$output = '<div class="wrap--quickhelp">';

	if ( class_exists('ACF') ) : 

		$qhgroup 	= get_field('opt_footer_quickhelp', 'option');
		$label 		= $qhgroup['label'];
		$content 	= $qhgroup['content'];

		$output .= "<a href='#wrap--quickhelp-content' class='undeco' data-rel='lightcase'>{$label}</a><div id='wrap--quickhelp-content' class='wrap--quickhelp-content' style='display: none;'><div class='quickhelp-content'>{$content}</div></div>"; 
	
	else : 

		$output .= __('Please install/activate the Advanced Custom Fields Pro plugin', 'lead');
	
	endif;

	$output .= '</div>';

	return $output;

}
add_shortcode( 'lead-quickhelp', 'lead_sc_output_quickhelp_url' );

/**
 * Output the copyright text specified on the LEAD Theme Settings options panel's 
 * Footer tab
 * 
 * @param  [type] $atts 	shortcode attributes array
 * @return [type]       	shortcode output
 */
function lead_sc_output_copyright( $atts ) {

	$a = shortcode_atts( array(
		//'foo' => 'something',
		//'bar' => 'something else',
	), $atts );

	$output = '<div class="wrap--copyright">';

	if ( class_exists('ACF') ) : $output .= get_field('opt_footer_copyright', 'option'); 
	else : $output .= __('Please install/activate the Advanced Custom Fields Pro plugin', 'lead');
	endif;

	$output .= '</div>';

	return $output;

}
add_shortcode( 'lead-copyright', 'lead_sc_output_copyright' );

/**
 * Output LEAD category archive
 * @param  [type] $atts    [description]
 * @param  [type] $content [description]
 * @return [type]          [description]
 */
function lead_sc_output_archive( $atts, $content = null ) {

	$a = shortcode_atts( array(
		'category' 	=> '',
		'posts' 	=> -1,
		'excerpt' 	=> true,
	), $atts );

	if ( is_string( $a['excerpt'] ) ) $a['excerpt'] = false;

	ob_start(); ?>

	<?php
	//Posted on December 28, 2018 by LeadPittsburgh
	?>

	<div class="wrap--lead-sc-archive">

		<div class="lead-sc-archive">
			
			<?php
			$page_ID = get_the_ID();

			$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

			$arch_args 	= array(
				'posts_per_page' 	=> $a['posts'],
				'category_name' 	=> $a['category'],
				'post_type' 		=> 'post',
				'post_status' 		=> array( 'publish', 'private' ),
				'paged'             => $paged,
			);

			$arch_query = new WP_Query( $arch_args );

			if ( $arch_query->have_posts() ) :

				echo '<ul>';

				while ( $arch_query->have_posts() ) :

					$arch_query->the_post();

					$permalink = get_permalink();

					echo '<li>';
					the_title(  "<h3 class='lead-sc-archive__etitle'><a href='{$permalink}'>", '</a></h3>' );

					if ( true == $a['excerpt'] ) : 

						the_excerpt();

					else :

						if ( get_the_post_thumbnail() != '' ) {

							echo '<a href="'; 
							the_permalink(); 
							echo '" class="thumbnail-wrapper">';
							the_post_thumbnail();
							echo '</a>';

						} else {

							echo '<a href="'; 
							the_permalink(); 
							echo '" class="thumbnail-wrapper">';
							echo '<img src="';
							echo catch_that_image();
							echo '" alt="" />';
							echo '</a>';

						}
						?>

						<a href="<?php the_permalink(); ?>" class="lead-sc-archive__readmore">Continue reading →</a>

					<?php
					endif;
					
					lead_posted_on();
					echo '</li>';
				
				endwhile;

				echo '</ul>';
				?>

				<div class="">

					<div class="">
						
						<?php 
					    $total_pages = $arch_query->max_num_pages;
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
						?>

					</div>

				</div>

				<?php														
				/* Restore original Post Data */
				wp_reset_postdata();

			endif;
			?>

		</div>

	</div>
	
	<?php
	$output = ob_get_clean();

	return $output;

}
add_shortcode( 'lead-archive', 'lead_sc_output_archive' );

/**
 * Output LEAD archive of posts set to be displayed on the Happenings page
 * @param  [type] $atts    [description]
 * @param  [type] $content [description]
 * @return [type]          [description]
 */
function lead_sc_output_happenings( $atts, $content = null ) {

	$a = shortcode_atts( array(
		'posts' 	=> -1,
	), $atts );

	global $wp_embed;

	ob_start(); ?>

	<div class="wrap--lead-sc-happenings">

		<div class="lead-sc-happenings">
			
			<?php
			$page_ID = get_the_ID();

			$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

			$happ_args 	= array(
				'posts_per_page' 	=> $a['posts'],
				'post_type' 		=> 'post',
				'post_status' 		=> array( 'publish', 'private' ),
				'paged'             => $paged,
				'meta_key'			=> 'd_in_happenings',
				'meta_value'		=> true,				
			);

			$happ_query = new WP_Query( $happ_args );

			if ( $happ_query->have_posts() ) : ?>

				<ul class="happening-blocks">
				
				<?php
				while ( $happ_query->have_posts() ) :

					$happ_query->the_post();

					$permalink 		= get_permalink();
					$happ_Group 	= get_field('p_as_happening');
					$media 			= $happ_Group['media'];

					$is_fullwidth 	= ( 
										empty($happ_Group) 		|| 
										( 'none' == $media ) 	|| 
										( NULL == $media ) 
									  );

					$happ_class 	= $is_fullwidth
										? 'excerpt-only full-width' 
										: 'happ-group-defined';					

					echo "<li class='happening-block {$happ_class} infinite-this'>";

						if ( false == $is_fullwidth ) :
						?>

							<div class="container">

								<div class="flex-container">
									
									<div class="flex-item wrap--happ-media">
	
										<div class="happ-media">

											<?php
											$img_Obj 	= $happ_Group['media_img'];
											$img_size 	= 'medium_large';
											$img_thumb 	= $img_Obj['sizes'][ $img_size ];

											$media_vid 	= $happ_Group['media_vid'];
											$has_yt 	= ( 
															$media_vid 				&& 
															( '' !== $media_vid ) 	&& 
															( NULL !== $media_vid ) 
														  );
											$yt_url 	= $has_yt 
															? 'https://www.youtube.com/embed/' . $media_vid
															: false;

											if ( 'img' == $media ) : 

												echo "<img src='{$img_thumb}' width='' height='' alt='' />";

											elseif ( ( 'vid' == $media ) && $yt_url ) :

												echo '<div class="project__video responsive-container">'; 

												// Specifying `global $wp_embed;` here would 
												// break the WPBakery Page Builder (!!!) - so
												// this definition can be found at the top of the shortcode, below the shortcode atts array def.
												echo $wp_embed->run_shortcode( '[embed]' . $yt_url . '[/embed]' );
												echo '</div>';
											
											endif;
											?>
		
										</div>

									</div>
									
									<div class="flex-item wrap--happ-excerpt">
										
										<div class="happ-excerpt">

											<?php
											the_title(  "<h3 class='lead-sc-happenings__etitle'><a href='{$permalink}'>", '</a></h3>' );

											echo '<div class="entry-excerpt">';

												$c_excerpt 	= $happ_Group['c_excerpt'];
												$add_btn 	= $happ_Group['add_btn'];
												$has_btn 	= $add_btn ? true : false;

												if ( $c_excerpt && ( '' !== $c_excerpt ) ) :
													echo $c_excerpt;
												else :
													the_excerpt();
												endif;

												if ( $has_btn ) :
													
													$btn_label 	= $happ_Group['btn_label'];						
													$btn_url 	= $happ_Group['btn_url'];
													$parsed_url = parse_url( $btn_url );
													$siteurl 	= get_site_url();				

													if ( isset( $parsed_url['host'] ) ) :						
														$id_href 	= ( $parsed_url['host'] == $siteurl );

													else :

														$id_href 	= true;

													endif;

													$lightboxed = $happ_Group['lightboxed'];

													$rel_val 	= $lightboxed ? 'lightcase' : 'darkcase';

													$target 	= $lightboxed || $id_href ? '_self' : '_blank';

													echo "<a href='$btn_url' class='lead-btn--ghost--small' data-rel='{$rel_val}' target='{$target}'>{$btn_label}</a>";

												endif;

											echo '</div>';

											?>

										</div>

									</div>

								</div>

							</div>
		
						<?php
						else: ?>

							<div>

								the_title(  "<h3 class='lead-sc-happenings__etitle'><a href='{$permalink}'>", '</a></h3>' );
								
								the_excerpt();

							</div>

						<?php
						endif;

					echo '</li>';
								
				endwhile; ?>

				</ul>

				<div class="wrap--happening-pagination container">

					<div class="happening-pagination infinite-navigation flex-container">
						
						<?php 
					    $total_pages = $happ_query->max_num_pages;
					    $big = 999999999; // need an unlikely integer

					    if ($total_pages > 1) :

					        $current_page = max(1, get_query_var('paged'));

							// get_next_posts_link() usage with max_num_pages
							echo '<div class="happ__nextnav flex-item">' . get_next_posts_link( 'Previous Posts', $happ_query->max_num_pages ) . '</div>';
							echo '<div class="happ__prevnav flex-item">' . get_previous_posts_link( 'Recent Posts' ) . '</div>';
					    
					    endif;
						?>

					</div>

				</div>

				<?php														
				/* Restore original Post Data */
				wp_reset_postdata();

			endif;
			?>

		</div>

	</div>
	
	<?php
	$output = ob_get_clean();

	return $output;

}
add_shortcode( 'lead-happenings', 'lead_sc_output_happenings' );

/**
 * Output project info
 * @param  [type] $atts    [description]
 * @param  [type] $content [description]
 * @return [type]          [description]
 */
function lead_sc_output_projectcard( $atts, $content = null ) {

	$a = shortcode_atts( array(
		'project' 	=> 'score',
	), $atts );

	ob_start(); 

	$has_acf = class_exists('ACF');
	if ( $has_acf ) :

		$card_Obj = ( 'core' == $a['project'] ) 
						? get_field('card_score', 'option') 
						: get_field('card_core', 'option');

		switch ( $a['project'] ) :
		
			case 'core':
				$card_Obj = get_field('card_core', 'option');
				break;
			
			case 'score':
				$card_Obj = get_field('card_score', 'option');		
				break;

			default:
				$card_Obj = get_field('card_score', 'option');
				break;
		
		endswitch;

		$logo 		= $card_Obj['logo']['url'];
		$content 	= $card_Obj['content'];
		?>

		<div class="wrap--lead-sc-project-card">

			<div class="lead-sc-project-card">
				
				<img class="project-logo" src="<?php echo $logo; ?>" width="" height="" alt="project logo">

				<div class="card-content">
					<?php echo $content; ?>
				</div>

			</div>

		</div>
	
		<?php
		$output = ob_get_clean();

	else :

		$output = __('Please install/activate the Advanced Custom Fields Pro plugin', 'lead');

	endif;

	return $output;

}
add_shortcode( 'lead-project-card', 'lead_sc_output_projectcard' );

/**
 * Output SecureTrust badge
 * @param  [type] $atts [description]
 * @return [type]       [description]
 */
function lead_sc_output_securetrust( $atts ) {

	$a = shortcode_atts( array(
		//
	), $atts );

	ob_start();
	?>

	<div class="wrap--securetrust">

		<div class="securetrust">

			<script type="text/javascript" src="https://sealserver.trustwave.com/seal.js?code=0a97c77637fe487190caeeb1dfe04069"></script>

		</div>

	</div>

	<?php

	return ob_get_clean();

}
add_shortcode( 'securetrust-badge', 'lead_sc_output_securetrust' );