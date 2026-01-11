<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Digital Newspaper
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->

	<?php digital_newspaper_post_thumbnail(); ?>

	<div class="entry-content">
		<?php
			the_content();

			wp_link_pages(
				array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'digital-newspaper' ),
					'after'  => '</div>',
				)
			);
		?>
	</div><!-- .entry-content -->

	<?php
	if ( is_page( 'quem-somos' ) && function_exists( 'publishpress_authors_get_all_authors' ) && function_exists( 'get_ppma_author_categories' ) ) :
		$editor_category_id = 0;
		$author_categories  = get_ppma_author_categories( array( 'category_status' => 1 ) );

		if ( is_array( $author_categories ) ) {
			foreach ( $author_categories as $author_category ) {
				if ( ! is_array( $author_category ) ) {
					continue;
				}

				$category_name = isset( $author_category['category_name'] ) ? (string) $author_category['category_name'] : '';
				$category_slug = isset( $author_category['slug'] ) ? (string) $author_category['slug'] : '';

				if ( 0 === strcasecmp( $category_name, 'Editor' ) || 0 === strcasecmp( $category_slug, 'editor' ) ) {
					$editor_category_id = isset( $author_category['id'] ) ? (int) $author_category['id'] : 0;
					break;
				}
			}
		}

		if ( $editor_category_id ) {
			$editors = publishpress_authors_get_all_authors( array(), array( 'category_id' => (string) $editor_category_id ) );
			if ( is_wp_error( $editors ) ) {
				$editors = array();
			}
			if ( isset( $editors['authors'] ) && is_array( $editors['authors'] ) ) {
				$editors = $editors['authors'];
			}
		}

		if ( ! empty( $editors ) && is_array( $editors ) ) :
			?>
			<section class="quem-somos-editors" aria-label="<?php echo esc_attr__( 'Editors', 'digital-newspaper' ); ?>">
				<?php
				foreach ( $editors as $editor ) :
					if ( is_array( $editor ) && isset( $editor['author'] ) ) {
						$editor = $editor['author'];
					}

					$editor_name  = '';
					$editor_title = '';
					$editor_bio   = '';
					$avatar_html  = '';

					if ( $editor instanceof WP_User ) {
						$editor_name  = $editor->display_name;
						$editor_title = get_user_meta( $editor->ID, 'job_title', true );
						$editor_bio   = get_the_author_meta( 'description', $editor->ID );
						$avatar_html  = get_avatar( $editor->ID, 160 );
					} elseif ( is_object( $editor ) ) {
						if ( method_exists( $editor, 'get_avatar' ) ) {
							$avatar_html = $editor->get_avatar( 160 );
						} elseif ( ! empty( $editor->user_email ) ) {
							$avatar_html = get_avatar( $editor->user_email, 160 );
						}

						if ( isset( $editor->display_name ) ) {
							$editor_name = $editor->display_name;
						}

						if ( method_exists( $editor, 'get_meta' ) ) {
							$editor_title = $editor->get_meta( 'job_title' );
						} elseif ( isset( $editor->job_title ) ) {
							$editor_title = $editor->job_title;
						}

						if ( method_exists( $editor, 'get_description' ) ) {
							$editor_bio = $editor->get_description();
						} elseif ( isset( $editor->description ) ) {
							$editor_bio = $editor->description;
						}
					}

					if ( '' === $editor_name && '' === $editor_title && '' === $editor_bio && '' === $avatar_html ) {
						continue;
					}
					?>
					<div class="quem-somos-editor">
						<div class="quem-somos-editor__avatar">
							<?php echo $avatar_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
						<div class="quem-somos-editor__info">
							<?php if ( $editor_name ) : ?>
								<h3 class="quem-somos-editor__name"><?php echo esc_html( $editor_name ); ?></h3>
							<?php endif; ?>
							<?php if ( $editor_title ) : ?>
								<p class="quem-somos-editor__title"><?php echo esc_html( $editor_title ); ?></p>
							<?php endif; ?>
							<?php if ( $editor_bio ) : ?>
								<div class="quem-somos-editor__bio">
									<?php echo wp_kses_post( wpautop( $editor_bio ) ); ?>
								</div>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</section>
		<?php endif; ?>
	<?php endif; ?>

	<?php if ( get_edit_post_link() ) : ?>
		<footer class="entry-footer">
			<?php
			edit_post_link(
				sprintf(
					wp_kses(
						/* translators: %s: Name of current post. Only visible to screen readers */
						__( 'Edit <span class="screen-reader-text">%s</span>', 'digital-newspaper' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					wp_kses_post( get_the_title() )
				),
				'<span class="edit-link">',
				'</span>'
			);
			?>
		</footer><!-- .entry-footer -->
	<?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->
