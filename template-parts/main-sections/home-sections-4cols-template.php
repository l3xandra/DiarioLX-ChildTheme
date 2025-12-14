<?php
/**
 * Home Section – Dynamic Latest Posts (4 Columns)
 */

// Now accepts category *array*
$categories = $args['categories'] ?? []; // array of 4 category slugs

$number   = $args['number'] ?? 4;
$order    = $args['order'] ?? 'DESC';
$orderby  = $args['orderby'] ?? 'date';

// Arrays for titles, links and colors
$titles = $args['article_titles'] ?? [];
$links  = $args['article_links'] ?? [];
$colors = $args['article_colors'] ?? [];
?>

<style>
    .news-list-4col {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.2rem;
    }

    @media (max-width: 1024px) {
        .news-list-4col { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 600px) {
        .news-list-4col { grid-template-columns: 1fr; }
    }
</style>

<div id="theme-content">
    <main id="primary"
        class="site-main <?php echo esc_attr('width-' . digial_newspaper_get_section_width_layout_val()); ?>">

        <div class="digital-newspaper-container">
            <div class="row four-col-row">
                <div class="primary-content">

                    <div class="news-list-wrap news-list-4col">

                    <?php
                    // Loop 4 times (one query per column)
                    for ($i = 0; $i < 4; $i++) :

                        $cat = $categories[$i] ?? ''; // category for this column

                        // Query for ONE post per column
                        $query_args = [
                            'posts_per_page' => 1,
                            'post_type'      => 'post',
                            'post_status'    => 'publish',
                            'order'          => $order,
                            'orderby'        => $orderby
                        ];

                        if (!empty($cat)) {
                            $query_args['category_name'] = $cat;
                        }

                        $q = new WP_Query($query_args);

                        echo '<div class="news-item">';

                        // Build custom section data for this index
                        $custom_section = [
                            'title' => $titles[$i] ?? '',
                            'link'  => $links[$i]  ?? '',
                            'color' => $colors[$i] ?? '#49D3FF',
                        ];

                        // Load article + section title
                        if ($q->have_posts()):
                            while ($q->have_posts()):
                                $q->the_post();

                                get_template_part(
                                    'template-parts/content-w-title-smaller',
                                    get_post_type(),
                                    ['custom_section' => $custom_section]
                                );

                            endwhile;
                            wp_reset_postdata();
                        endif;

                        echo '</div>';

                    endfor;
                    ?>

                    </div>

                </div>
            </div>
        </div>
    </main>
</div>
