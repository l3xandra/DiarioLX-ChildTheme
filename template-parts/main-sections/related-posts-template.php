<?php
/**
 * Single Related Posts – Reusable template
 *
 * Expected $args:
 * - query  → instance of WP_Query with the related posts
 */

if ( empty( $args['query'] ) || ! ( $args['query'] instanceof WP_Query ) ) {
    return;
}

$related_query = $args['query'];
?>

<div id="theme-content" class="single-related-posts theme-related">
    <main id="primary"
          class="site-main <?php echo esc_attr( 'width-' . digial_newspaper_get_section_width_layout_val() ); ?>">
        <div class="digital-newspaper-container">
            <div class="row">

                <div class="primary-content">

                    <?php if ( $related_query->have_posts() ) : ?>
                        <div class="news-list-wrap">
                            <?php
                            while ( $related_query->have_posts() ) :
                                $related_query->the_post();

                                // Reuse the same card layout as on the home sections
                                get_template_part( 'template-parts/content', get_post_type() );

                            endwhile;

                            // Important: restore global $post
                            wp_reset_postdata();
                            ?>
                        </div>
                    <?php endif; ?>

                </div>

            </div>
        </div>
    </main>
</div>
