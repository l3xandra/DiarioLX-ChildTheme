<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Digital Newspaper
 */
use Digital_Newspaper\CustomizerDefault as DN;
?>
<article <?php digital_newspaper_schema_article_attributes(); ?> id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
	/**
	 * PREPARE LEAD / ENTRY
	 * We take the first <strong>...</strong> from the content and use it as the entry.
	 */
	$raw_content = get_post_field('post_content', get_the_ID());
	$entry_lead = '';

	if (has_excerpt()) {
		$entry_lead = get_the_excerpt();
	}
	?>

	<div class="post-inner">
		<header class="entry-header entry-header--with-thumb">
			<div class="entry-header-inner">

				<!-- LEFT: IMAGE -->
				<div class="entry-header-media">
					<?php
					// Thumbnail on the left
					digital_newspaper_post_thumbnail();
					?>
				</div>

				<!-- RIGHT: CATEGORY, TITLE, META -->
				<div class="entry-header-content">

					<div class="meta-header-post">
						<?php digital_newspaper_get_post_categories(get_the_ID(), 0);
						// Meta (author, date, comments, read time)
						if ('post' === get_post_type()): ?>
							<div class="entry-meta">
								<?php
								if (function_exists('digital_newspaper_posted_on')) {
									digital_newspaper_posted_on(); // ← this prints the date
								}
								?>
							</div><!-- .entry-meta -->
						<?php endif; ?>
					</div>
					<?php
					// Categories
					

					// Title
					the_title(
						'<h1 class="entry-title"' . digital_newspaper_schema_article_name_attributes() . '>',
						'</h1>'
					);

					?>


					<?php /** LEAD */ if (!empty($entry_lead)): ?>

						<p class="entry-lead">
							<?php echo $entry_lead; ?>
						</p>
					<?php endif; ?>

				</div><!-- .entry-header-content -->

			</div><!-- .entry-header-inner -->
		</header><!-- .entry-header -->
		<!-- .entry-header -->



		<!-- WRAPPER: AUTORES (ESQ) + TEXTO (CENTRO) -->
		<div class="single-layout-wrapper">

			<!-- COLUNA AUTORES (ESPAÇO ESQUERDO) -->
			<aside class="single-authors-column">


				<?php if ('post' === get_post_type()): ?>

					<!-- LINHA AUTORES -->
					<div class="single-authors-row">
						<span class="single-authors-title">
							<?php esc_html_e('POR', 'digital-newspaper'); ?>
						</span>

						<div class="single-authors-list">
							<?php
							// Usa a função que já tens para mostrar autores (inclui multi-autores via plugin)
							if (function_exists('digital_newspaper_posted_by')) {
								digital_newspaper_posted_by();
							}
							?>
						</div>
					</div>

					<?php
					// Buscar o fotógrafo guardado no meta _dlx_photo_user
					$photo_user_id = (int) get_post_meta(get_the_ID(), '_dlx_photo_user', true);

					if ($photo_user_id):
						$photo_name = get_the_author_meta('display_name', $photo_user_id);
						$photo_url = get_author_posts_url($photo_user_id);
						?>
						<!-- LINHA FOTOGRAFIA -->
						<div class="single-authors-row">
							<span class="single-authors-title">
								<?php esc_html_e('FOTOGRAFIA', 'digital-newspaper'); ?>
							</span>

							<div class="single-authors-list">
								<span class="author vcard">
									<a href="<?php echo esc_url($photo_url); ?>" class="url fn n author_name">
										<?php echo esc_html($photo_name); ?>
									</a>
								</span>
							</div>
						</div>
					<?php endif; ?>

				<?php endif; ?>
			</aside>



			<!-- COLUNA DE TEXTO (NO CENTRO) -->
			<div <?php digital_newspaper_schema_article_body_attributes(); ?> class="entry-content single-collumn">
				<?php
				$filtered_content = apply_filters('the_content', $raw_content);
				echo $filtered_content;

				wp_link_pages(
					array(
						'before' => '<div class="page-links">' . esc_html__('Pages:', 'digital-newspaper'),
						'after' => '</div>',
					)
				);
				?>
				<footer class="entry-footer">
					<?php if (has_tag()): ?>
						<span class="single-tags-label">
							<?php esc_html_e('TAGS', 'digital-newspaper'); ?>
						</span>

						<?php digital_newspaper_tags_list(); ?>

					<?php endif; ?>
				</footer><!-- .entry-footer -->
			</div><!-- .entry-content -->

		</div><!-- .single-layout-wrapper -->





	</div>

	<?php
	// If comments are open or we have at least one comment, load up the comment template.
	if (comments_open() || get_comments_number()):
		comments_template();
	endif;
	?>
</article><!-- #post-<?php the_ID(); ?> -->