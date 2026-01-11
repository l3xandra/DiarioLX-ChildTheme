<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Digital Newspaper
 */
use Digital_Newspaper\CustomizerDefault as DN;

add_action( 'wp_head', function () {
	echo '<style>body.page #theme-content{padding-top:80px;} .page .entry-header{border-bottom:0.5px solid currentColor;} .page .entry-title{margin-bottom:10px;} .page .post-inner-wrapper{max-width:680px;margin-left:auto;margin-right:auto;} .page .entry-header, .page .entry-content{width:100%;} .page .entry-content{margin-top:20px !important;} .page .post-thumbnail{margin-top:20px !important;} .page .entry-header + .post-thumbnail{margin-top:20px !important;} .page .post-thumbnail + .entry-content{margin-top:0;} .quem-somos-editors{margin-top:50px;display:flex;flex-direction:column;gap:24px;} .quem-somos-editor{display:grid !important;grid-template-columns:30% 1fr !important;column-gap:24px;align-items:start;} .quem-somos-editor__avatar{max-width:none;display:flex;justify-content:center;align-items:center;} .quem-somos-editor__avatar img{width:140px !important;height:140px !important;max-width:none !important;border-radius:50%;object-fit:cover;} .quem-somos-editor__info{min-width:0;} .quem-somos-editor__name{margin:0;font-size:30px;font-weight:500;} .quem-somos-editor__title{margin:0 0 10px;font-weight:600;} .quem-somos-editor__bio{margin-top:20px;border-top:0.1px solid currentColor;padding-top:10px;font-family:"Lora", serif;} .quem-somos-editor__bio p{margin:0 0 10px;} </style>';
}, 20 );

get_header();

if( is_front_page() ) :
	/**
	 * hook - digital_newspaper_main_banner_hook
	 * 
	 * hooked - digital_newspaper_main_banner_part - 10
	 */
	do_action( 'digital_newspaper_main_banner_hook' );

	$homepage_content_order = DN\digital_newspaper_get_customizer_option( 'homepage_content_order' );
	foreach( $homepage_content_order as $content_order_key => $content_order ) :
		if( $content_order['option'] ) :
			switch( $content_order['value'] ) {
				case "full_width_section": 
									/**
									 * hook - digital_newspaper_full_width_blocks_hook
									 * 
									 * hooked- digital_newspaper_full_width_blocks_part
									 * @since 1.0.0
									 * 
									 */
									do_action( 'digital_newspaper_full_width_blocks_hook' );
								break;
				case "leftc_rights_section": 
									/**
									 * hook - digital_newspaper_leftc_rights_blocks_hook
									 * 
									 * hooked- digital_newspaper_leftc_rights_blocks_part
									 * @since 1.0.0
									 * 
									 */
									do_action( 'digital_newspaper_leftc_rights_blocks_hook' );
								break;
				case "lefts_rightc_section": 
									/**
									 * hook - digital_newspaper_lefts_rightc_blocks_hook
									 * 
									 * hooked- digital_newspaper_lefts_rightc_blocks_part
									 * @since 1.0.0
									 * 
									 */
									do_action( 'digital_newspaper_lefts_rightc_blocks_hook' );
								break;
				case "bottom_full_width_section": 
									/**
									 * hook - digital_newspaper_bottom_full_width_blocks_hook
									 * 
									 * hooked- digital_newspaper_bottom_full_width_blocks_part
									 * @since 1.0.0
									 * 
									 */
									do_action( 'digital_newspaper_bottom_full_width_blocks_hook' );
								break;
					default: ?>
					<div id="theme-content">
						<?php
							/**
							 * hook - digital_newspaper_before_main_content
							 * 
							 */
							do_action( 'digital_newspaper_before_main_content' );
						?>
						<main id="primary" class="site-main">
							<div class="digital-newspaper-container">
								<div class="row">
								<div class="secondary-left-sidebar">
										<?php
											get_sidebar('left');
										?>
									</div>
									<div class="primary-content">
										<?php
											/**
											 * hook - digital_newspaper_before_inner_content
											 * 
											 */
											do_action( 'digital_newspaper_before_inner_content' );
										?>
										<div class="post-inner-wrapper">
											<?php
												while ( have_posts() ) :
													the_post();

													get_template_part( 'template-parts/content', 'page' );

													// If comments are open or we have at least one comment, load up the comment template.
													if ( comments_open() || get_comments_number() ) :
														comments_template();
													endif;

												endwhile; // End of the loop.
											?>
										</div>
									</div>
									<div class="secondary-sidebar">
										<?php get_sidebar(); ?>
									</div>
								</div>
							</div>
						</main><!-- #main -->
					</div><!-- #theme-content -->
				<?php
			}
		endif;
	endforeach;
else :
?>
	<div id="theme-content">
		<?php
			/**
			 * hook - digital_newspaper_before_main_content
			 * 
			 */
			do_action( 'digital_newspaper_before_main_content' );
		?>
		<main id="primary" class="site-main <?php echo esc_attr( 'width-' . digial_newspaper_get_section_width_layout_val() ); ?>">
			<div class="digital-newspaper-container">
				<div class="row">
				<div class="secondary-left-sidebar">
						<?php
							get_sidebar('left');
						?>
					</div>
					<div class="primary-content">
						<?php
							/**
							 * hook - digital_newspaper_before_inner_content
							 * 
							 */
							do_action( 'digital_newspaper_before_inner_content' );
						?>
						<div class="post-inner-wrapper">
							<?php
								while ( have_posts() ) :
									the_post();

									get_template_part( 'template-parts/content', 'page' );

									// If comments are open or we have at least one comment, load up the comment template.
									if ( comments_open() || get_comments_number() ) :
										comments_template();
									endif;

								endwhile; // End of the loop.
							?>
						</div>
					</div>
					<div class="secondary-sidebar">
						<?php get_sidebar(); ?>
					</div>
				</div>
			</div>
		</main><!-- #main -->
	</div><!-- #theme-content -->
<?php
endif;
get_footer();