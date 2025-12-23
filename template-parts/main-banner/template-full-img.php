<?php
/**
 * Template part: Category hero (title + featured banner code inline)
 *
 * Usage:
 * get_template_part('template-parts/archive/category-hero', null, [
 *   'category'  => 'podcasts',   // slug (optional; will fallback to queried category)
 *   'title'     => 'Podcasts',   // optional override
 *   'color'     => '#ff0066',    // optional override
 *   'font_size' => '50px',       // optional
 * ]);
 */

use Digital_Newspaper\CustomizerDefault as DN;

$category_slug = isset($args['category']) ? sanitize_title($args['category']) : '';
$override_title = $args['title'] ?? '';
$override_color = $args['color'] ?? '';
$font_size = $args['font_size'] ?? '50px';

/**
 * Resolve category term
 */
$term = null;

if ($category_slug) {
    $term = get_term_by('slug', $category_slug, 'category');
} else {
    $q = get_queried_object();
    if ($q && !is_wp_error($q) && !empty($q->term_id) && isset($q->taxonomy) && $q->taxonomy === 'category') {
        $term = $q;
        $category_slug = $term->slug;
    }
}

if (!$term || is_wp_error($term) || empty($term->term_id)) {
    return;
}

$category_id = (int) $term->term_id;
$category_name = $term->name;

/**
 * Title (override > term name)
 */
$title = $override_title ? $override_title : $category_name;

/**
 * Color (override > customizer > fallback)
 */
$category_color = '#000000';

if ($override_color) {
    $category_color = $override_color;
} else {
    $color_data = DN\digital_newspaper_get_customizer_option('category_' . absint($category_id) . '_color');

    if (is_array($color_data) && !empty($color_data['color'])) {
        if (function_exists('digital_newspaper_get_color_format')) {
            $category_color = digital_newspaper_get_color_format($color_data['color']);
        } else {
            $category_color = $color_data['color'];
        }
    }
}

/**
 * ==========================
 * INLINE BANNER CODE (your template part code)
 * ==========================
 */
$top_post = new WP_Query([
    'posts_per_page' => 1,
    'post_type' => 'post',
    'post_status' => 'publish',
    'category_name' => $category_slug,
]);

if ($top_post->have_posts()):
    while ($top_post->have_posts()):
        $top_post->the_post();
        ?>
        <div class="digital-newspaper-container digital-newspaper-container-larger lca-shorter">
            <div class="row">
                <div class="top-main-banner-wrap">
                    <div class="top-main-banner-inner">

                        <article class="top-main-banner-item <?php if (!has_post_thumbnail())
                            echo 'no-feat-img'; ?>">

                            <figure class="post-thumb">
                                <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                                    <?php
                                    if (has_post_thumbnail()) {
                                        the_post_thumbnail('digital-newspaper-featured', [
                                            'title' => the_title_attribute(['echo' => false])
                                        ]);
                                    }
                                    ?>
                                </a>
                            </figure>

                            <div class="post-element-wrap lca-center-homepage">
                                <div class="digital-newspaper-container post-element lca-homepage">
                                    <div class="back-to-normal-width">
                                        <div class="dn-narrow-wrap">
                                            <div class="post-meta">
                                                <?php digital_newspaper_get_post_categories(get_the_ID(), 2); ?>
                                            </div>
                                            <h2 class="post-title t1B">
                                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                            </h2>
                                            <div class="post-excerpt main-post-home-lead"><?php the_excerpt(); ?></div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </article>
                    </div>
                </div>
            </div>
        </div>
        <?php
    endwhile;
    wp_reset_postdata();
endif;
