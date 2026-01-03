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
$number = $args['number'] ?? 5;
$order = $args['order'] ?? 'DESC';
$orderby = $args['orderby'] ?? 'date';

?>


<div id="theme-content">
    <main id="primary"
        class="site-main <?php echo esc_attr('width-' . digial_newspaper_get_section_width_layout_val()); ?>">
        <div class="digital-newspaper-container">
            <div class="row">

                <div class="lca-primary-column margin-bottom">

                    <?php
                    // Build query
                    $query_args = [
                        'posts_per_page' => $number,
                        'post_type' => 'post',
                        'post_status' => 'publish',
                        'order' => $order,
                        'orderby' => $orderby,
                    ];

                    // If category is set — use it
                    if (!empty($category)) {
                        $query_args['category_name'] = $category;
                    }

                    $latest_posts = new WP_Query($query_args);

                    if ($latest_posts->have_posts()):

                        $posts = $latest_posts->posts; // array of WP_Post
                        global $post;

                        echo '<div class="lca-batches">';

                        // ROW 1: [1] + [3]
                        echo '<div class="lca-batch-row lca-row-top  border-gray">';

                        echo '<div class="lca-col-left">';
                        if (!empty($posts[0])) {
                            $post = $posts[0];
                            setup_postdata($post);
                            get_template_part('template-parts/content-text-other-side-1-2', '1'); // post-1.php
                        }
                        echo '</div>';


                        echo '<div class="lca-col-right">';
                        if (!empty($posts[2])) { // "3rd post" in the top-right
                            $post = $posts[2];
                            setup_postdata($post);
                            get_template_part(
                                'template-parts/content-w-title-smaller',
                                get_post_type()
                            );
                        }
                        echo '</div>';

                        echo '</div>';

                        // ROW 2: [2] + [4+5]
                        echo '<div class="lca-batch-row lca-row-bottom">';

                        echo '<div class="lca-col-left">';
                        if (!empty($posts[1])) {
                            $post = $posts[1];
                            setup_postdata($post);
                            get_template_part('template-parts/content-text-side-no-lead', '2'); // post-2.php
                        }
                        echo '</div>';

                        echo '<div class="lca-col-right">';
                        echo '<div class="lca-slot-45">';
                        for ($i = 3; $i <= 4; $i++) {
                            if (empty($posts[$i]))
                                continue;
                            $post = $posts[$i];
                            setup_postdata($post);
                            get_template_part('template-parts/content-no-image', 'small'); // post-small.php
                        }
                        echo '</div>';
                        echo '</div>';

                        echo '</div>';

                        echo '</div>';

                        wp_reset_postdata();

                    endif;

                    ?>

                </div>

            </div>
        </div>
    </main>
</div>
