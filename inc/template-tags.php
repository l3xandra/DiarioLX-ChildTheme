<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Digital Newspaper
 */
use Digital_Newspaper\CustomizerDefault as DN;

if ( ! function_exists( 'digital_newspaper_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function digital_newspaper_posted_on( $post_id = '' ) {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		$time = $post_id ? get_the_time( 'U', $post_id ) : get_the_time( 'U' );
		$modified_time = $post_id ? get_the_modified_time( 'U', $post_id ) : get_the_modified_time( 'U' );
		if ( $time !== $modified_time ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf(
			$time_string,
			esc_attr( $post_id ? get_the_date( DATE_W3C, $post_id ) : get_the_date( DATE_W3C ) ),
			esc_html( digital_newspaper_get_published_date($post_id) ),
			esc_attr( $post_id ? get_the_modified_date( DATE_W3C, $post_id ) : get_the_modified_date( DATE_W3C ) ),
			esc_html( digital_newspaper_get_modified_date($post_id) )
		);
		$posted_on = '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>';
		echo '<span class="post-date posted-on ' .esc_attr( DN\digital_newspaper_get_customizer_option( 'site_date_to_show' ) ). '">' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
endif;

if ( ! function_exists( 'digital_newspaper_posted_by' ) ) :
	/**
	 * Prints HTML with meta information for the current author.
	 */
	function digital_newspaper_posted_by($post_id = '') {
		$author_id = $post_id ? get_post_field( 'post_author', $post_id ) : get_the_author_meta( 'ID' );
		$author_name = $post_id ? get_the_author_meta( 'display_name' , $author_id ) : get_the_author();
		$byline =  '<span class="author vcard"><a class="url fn n author_name" href="' . esc_url( get_author_posts_url( $author_id ) ) . '">' . esc_html( $author_name ) . '</a></span>';
		echo '<span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
endif;

if ( ! function_exists( 'digital_newspaper_comments_number' ) ) :
	/**
	 * Prints HTML with meta information for the current comments number.
	 */
	function digital_newspaper_comments_number() {
		echo '<span class="post-comment">' .absint( get_comments_number() ). '</span>';
	}
endif;


if ( ! function_exists( 'digital_newspaper_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function digital_newspaper_entry_footer() {
		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link(
				sprintf(
					wp_kses(
						/* translators: %s: post title */
						__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'digital-newspaper' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					wp_kses_post( get_the_title() )
				)
			);
			echo '</span>';
		}

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
	}
endif;

if( ! function_exists( 'digital_newspaper_categories_list' ) ) :
	/**
	 * print the html for categories list
	 */
	function digital_newspaper_categories_list() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ', ', 'digital-newspaper' ) );
			if ( $categories_list ) {
				/* translators: 1: list of categories. */
				printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'digital-newspaper' ) . '</span>', $categories_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}
	}
endif;

if( ! function_exists( 'digital_newspaper_tags_list' ) ) :
	/**
	 * print the html for tags list
	 */
	function digital_newspaper_tags_list() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', ' ' );
			if ( $tags_list ) {
				/* translators: 1: list of tags. */
				printf( '<span class="tags-links">' . esc_html__( 'Tagged: %1$s', 'digital-newspaper' ) . '</span>', $tags_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}
	}
endif;

if ( ! function_exists( 'digital_newspaper_post_thumbnail' ) ) :
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 */
	function digital_newspaper_post_thumbnail() {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}
		
		if ( is_singular() ) :
		?>
			<div class="post-thumbnail">
				<?php the_post_thumbnail(); ?>
			</div><!-- .post-thumbnail -->
		<?php else : ?>

			<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
				<?php
					the_post_thumbnail(
						'post-thumbnail',
						array(
							'alt' => the_title_attribute(
								array(
									'echo' => false
								)
							),
						)
					);
				?>
			</a>
			<?php
		endif; // End is_singular().
	}
endif;





if( ! function_exists( 'digital_newspaper_get_published_date' ) ) :
	// Get post pusblished date
	function digital_newspaper_get_published_date($post_id='') {
		$site_date_format = DN\digital_newspaper_get_customizer_option( 'site_date_format' );
		$n_date = $site_date_format == 'default' ? 
												$post_id ? get_the_date('', $post_id) : get_the_date() : 
												human_time_diff($post_id ? get_the_time('U',$post_id) : get_the_time('U'), current_time('timestamp')) .' '. __('', 'digital-newspaper');
		return apply_filters( "digital_newspaper_inherit_published_date", $n_date );
	}
endif;

if( ! function_exists( 'digital_newspaper_get_modified_date' ) ) :
	// Get post pusblished date
	function digital_newspaper_get_modified_date($post_id='') {
		$site_date_format = DN\digital_newspaper_get_customizer_option( 'site_date_format' );
		$n_date = $site_date_format == 'default' ? 
											$post_id ? get_the_modified_date('', $post_id) : get_the_modified_date() : 
												human_time_diff($post_id ? get_the_modified_time('U', $post_id): get_the_modified_time('U'), current_time('timestamp')) .' '. __('', 'digital-newspaper');
		return apply_filters( "digital_newspaper_inherit_published_date", $n_date );
	}
endif;

if ( ! function_exists( 'wp_body_open' ) ) :
	/**
	 * Shim for sites older than 5.2.
	 *
	 * @link https://core.trac.wordpress.org/ticket/12563
	 */
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
endif;

if( ! function_exists( 'digital_newspaper_get_post_categories' ) ) :
    /**
     * Show first tag but using the category color
     */
    function digital_newspaper_get_post_categories( $post_id, $number ) {

        // Get FIRST CATEGORY of the post (for the color)
        $categories = get_the_category( $post_id );
        $cat_color_class = '';

        if ( !empty($categories) ) {
            $main_cat = $categories[0];
            $cat_color_class = 'cat-' . $main_cat->term_id; // color comes from here
        }

        // Get FIRST TAG of the post (for the text)
        $tags = get_the_tags( $post_id );

        if ( $tags && ! is_wp_error( $tags ) ) {
            $first_tag = $tags[0];

            echo '<ul class="post-categories">';
                echo '<li class="cat-item ' . esc_attr( $cat_color_class ) . '">';
                    echo '<a href="' . esc_url( get_tag_link( $first_tag->term_id ) ) . '" rel="tag">';
                        echo esc_html( $first_tag->name );
                    echo '</a>';
                echo '</li>';
            echo '</ul>';
        }
    }
endif;
