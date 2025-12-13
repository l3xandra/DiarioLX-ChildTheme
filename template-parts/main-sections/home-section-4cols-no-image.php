<?php
/**
 * Home Section – Latest Posts (4 Columns, No Args)
 */

// Always fetch last 4 posts
$query_args = [
    'posts_per_page' => 4,
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC'
];
?>

<style>
    /* SCOPE EVERYTHING UNDER THIS CLASS */
    .fourcols-noimg-wrapper .news-list-4col {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.2rem;
    }

    .fourcols-noimg-wrapper .news-item {
        padding-right: 1.2rem;
        border-right: 1px solid #ddd;
    }

    /* DESKTOP — 4 columns */
    @media (min-width: 1025px) {
        .fourcols-noimg-wrapper .news-item:nth-child(4n) {
            border-right: none;
            padding-right: 0 !important;
        }
    }

    /* TABLET — 2 columns */
    @media (max-width: 1024px) and (min-width: 601px) {
        .fourcols-noimg-wrapper .news-item:nth-child(2n) {
            border-right: none;
            padding-right: 0 !important;
        }
    }

    /* MOBILE — 1 column */
    @media (max-width: 600px) {
        .fourcols-noimg-wrapper .news-item {
            border-right: none;
            padding-right: 0 !important;
        }
    }
</style>





<div id="theme-content" class="fourcols-noimg-wrapper">
    <main id="primary"
        class="site-main <?php echo esc_attr('width-' . digial_newspaper_get_section_width_layout_val()); ?>">

        <div class="digital-newspaper-container">
            <div class="row four-col-row">

                <div class="primary-content">

                    <?php
                    // Query
                    $latest_posts = new WP_Query($query_args);

                    if ($latest_posts->have_posts()):
                        
                        echo '<div class="news-list-wrap news-list-4col">';

                        while ($latest_posts->have_posts()):
                            $latest_posts->the_post();

                            echo '<div class="news-item">';
                                get_template_part('template-parts/content-no-image', get_post_type());
                            echo '</div>';

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