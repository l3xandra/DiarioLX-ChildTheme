<?php
/**
 * Home Section – Dynamic Latest Posts
 * Reusable template with arguments
 *
 * Accepted arguments:
 * - category  → category slug (string)
 * - number    → number of posts (int)
 * - order     → ASC / DESC
 * - orderby   → date / title / modified / rand / etc
 */

$category = $args['category'] ?? '';
$number   = $args['number'] ?? 3;
$order    = $args['order'] ?? 'DESC';
$orderby  = $args['orderby'] ?? 'date';
$custom_query = $args['query'] ?? null;

?>


<div id="theme-content">
    <main id="primary"
        class="site-main <?php echo esc_attr('width-' . digial_newspaper_get_section_width_layout_val()); ?>">
        <div class="digital-newspaper-container">
            <div class="row">

                <div class="primary-content margin-bottom">

                    <?php
                    $latest_posts = null;

                    if ($custom_query instanceof WP_Query) {
                        $latest_posts = $custom_query;
                    } else {
                        // Build query
                        $query_args = [
                            'posts_per_page' => $number,
                            'post_type'      => 'post',
                            'post_status'    => 'publish',
                            'order'          => $order,
                            'orderby'        => $orderby,
                        ];

                        // If category is set — use it
                        if (!empty($category)) {
                            $query_args['category_name'] = $category;
                        }

                        $latest_posts = new WP_Query($query_args);
                    }

                    if ($latest_posts->have_posts()):
                        echo '<div class="news-list-wrap">';

                        while ($latest_posts->have_posts()):
                            $latest_posts->the_post();
                            get_template_part('template-parts/content-videos-homepage', get_post_type());
                        endwhile;

                        echo '</div>';
                        wp_reset_postdata();
                    endif;
                    ?>

                </div>

            </div>
        </div>
    </main>
</div>