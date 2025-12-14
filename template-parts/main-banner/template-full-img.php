<?php
/**
 * Template part: Full Image (title + featured banner)
 * Usage:
 * get_template_part('template-parts/main-banner/template-full-img', null, [
 *   'category'  => 'podcasts',   // slug
 *   'title'     => 'Podcasts',   // opcional (override)
 *   'color'     => '#ff0066',    // opcional (override)
 *   'font_size' => '50px',       // opcional
 * ]);
 */

use Digital_Newspaper\CustomizerDefault as DN;

$category_slug = isset($args['category']) ? sanitize_title($args['category']) : '';
$override_title = $args['title'] ?? '';
$override_color = $args['color'] ?? '';
$font_size      = $args['font_size'] ?? '50px';

/**
 * Se não vier slug por args, tenta apanhar do contexto (ex: arquivo de categoria)
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
    return; // nada para mostrar
}

$category_id   = (int) $term->term_id;
$category_name = $term->name;

/**
 * Título final (override > nome do termo)
 */
$title = $override_title ? $override_title : $category_name;

/**
 * Cor final (override > Customizer > fallback)
 */
$category_color = '#000000';

if ($override_color) {
    $category_color = $override_color;
} else {
    $categoria_color_data = DN\digital_newspaper_get_customizer_option('category_' . absint($category_id) . '_color');

    if (is_array($categoria_color_data) && !empty($categoria_color_data['color'])) {
        if (function_exists('digital_newspaper_get_color_format')) {
            $category_color = digital_newspaper_get_color_format($categoria_color_data['color']);
        } else {
            $category_color = $categoria_color_data['color'];
        }
    }
}

/**
 * Escolha de templates por slug
 */
$title_section = 'inc/section-title-page';
$banner_template = 'template-parts/main-banner/template-six-banner';

if ($category_slug === 'podcasts' || $category_slug === 'videos') {
    $title_section   = 'inc/section-title-page-pods';
    $banner_template = 'template-parts/main-banner/template-six-banner-pods';
}

/**
 * Render
 */
get_template_part(
    $title_section,
    null,
    [
        'title'     => $title,
        'color'     => $category_color,
        'font_size' => $font_size,
    ]
);

get_template_part(
    $banner_template,
    null,
    [
        'category' => $category_slug,
    ]
);
