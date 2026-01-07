<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Digital Newspaper
 */
use Digital_Newspaper\CustomizerDefault as DN;
get_header();

/**
 * NOTICIA PRINCIPALL HOMEPAGE
 */
$top_post = new WP_Query([
	'posts_per_page' => 1,
	'post_type' => 'post',
	'post_status' => 'publish',
	'meta_key' => '_escs_is_main_article',
	'meta_value' => 'yes',
	'ignore_sticky_posts' => true,
]);

if ($top_post->have_posts()):
	while ($top_post->have_posts()):
		$top_post->the_post();
		?>

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

					<div class="post-element">
						<div class="digital-newspaper-container">
							<div class="dn-narrow-wrap">
								<div class="post-meta">
									<?php digital_newspaper_get_post_categories(get_the_ID(), 2); ?>
								</div>

								<h2 class="post-title">
									<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
								</h2>

								<div class="post-excerpt"><?php the_excerpt(); ?></div>
							</div>
						</div>
					</div>

				</article>

			</div>
		</div>

		<?php
	endwhile;
	wp_reset_postdata();
endif;


/**
 * SECCAO 3 noticias em destaque
 */
$featured_posts = new WP_Query([
	'post_type'      => 'post',
	'posts_per_page' => 3,
	'post_status'    => 'publish',
	'meta_key'       => '_is_ns_featured_post',
	'meta_value'     => 'yes',
	'ignore_sticky_posts' => true,
]);

get_template_part(
	'template-parts/main-sections/home-sections-template',
	null,
	[
		'query' => $featured_posts
	]
);


/***
 * ULTIMAS NOTICIAS
 */
get_template_part('inc/section_title', null, [
	'title' => 'Últimas',
	'link' => '/category/lisboacidadeaberta/',
	'color' => '#000000' // ← dynamic color here!
]);

get_template_part('template-parts/main-sections/home-section-4cols-no-image');


/***
 * LISBOA CIDADE ABERTA TITULO
 */
$slug = 'lisboacidadeaberta';
$term = get_category_by_slug($slug);

$color = ($term && !is_wp_error($term))
	? dlx_get_theme_category_color($term->term_id)
	: '#49D3FF';


get_template_part('inc/section-title-bigger', null, [
	'title' => 'Lisboa, Cidade Aberta',
	'link' => "/category/$slug/",
	'color' => $color,
	'font_size' => '45px',
	'row_class' => 'section-title-row--bottom',
]);


/**
 * MAIN BANNER - SECCAO IMPORTANTE - Lisboa, Cidade Aberta - MAIN POST
 */
/**if (is_home() && is_front_page()) {
	do_action('digital_newspaper_main_banner_hook');
}**/

$lca_slug = 'lisboacidadeaberta';
$lca_top_post_id = 0;
$lca_top_query = new WP_Query([
	'posts_per_page' => 1,
	'post_type' => 'post',
	'post_status' => 'publish',
	'category_name' => $lca_slug,
]);

if ($lca_top_query->have_posts()) {
	$lca_top_query->the_post();
	$lca_top_post_id = get_the_ID();
}
wp_reset_postdata();

get_template_part('template-parts/main-banner/template-full-img', null, [
	'category' => $lca_slug,
	'title' => 'Lisboa, Cidade Aberta',
	'font_size' => '50px',
]);

/**
 * LCA - NEXT POSTS
 */

get_template_part(
	'template-parts/main-sections/home-sections-text-side-exclude',
	null,
	[
		'category' => $lca_slug,
		'exclude' => $lca_top_post_id ? [$lca_top_post_id] : [],
	]
);

echo '<div style="margin-top:70px;">';


/**** SECCOES */
get_template_part('inc/section_title', null, [
	'title' => 'Secções',
	'link' => '/category/seccoes/',
	'color' => '#000000' // ← dynamic color here!
]);

get_template_part(
	'template-parts/main-sections/home-template-seccoes',
	null,
	[
		'category' => 'seccoes'
	]
);


echo '<div style="margin-top:50px;">';



/***
 * SECCAO - SECCOES: Internacional, Politica, Sociedade, Economia
 */
/*get_template_part(
	'template-parts/main-sections/home-sections-4cols-template',
	null,
	[
		'categories' => [
			'mundo',
			'politica',
			'sociedade',
			'economia'
		],
		'article_titles' => [
			'Mundo',
			'Política',
			'Sociedade',
			'Economia'
		],
		'article_links'  => [
			'/category/mundo/',
			'/category/politica/',
			'/category/sociedade/',
			'/category/economia/'
		],
		'article_colors' => [
			'#000000',
			'#000000',
			'#000000',
			'#000000'
		]
	]
);*/







/** SECCAO - A FUNDO + TITULO*/
get_template_part('inc/section_title', null, [
	'title' => 'A Fundo',
	'link' => '/category/afundo/',
	'color' => '#000000' // ← dynamic color here!
]);

get_template_part(
	'template-parts/main-sections/home-sections-template',
	null,
	[
		'category' => 'afundo'
	]
);



/** SECCAO - Especiais + TITULO*/
get_template_part('inc/section_title', null, [
	'title' => 'Especiais',
	'link' => '/category/especiais/',
	'color' => '#000000' // ← dynamic color here!
]);

get_template_part(
	'template-parts/main-sections/home-sections-text-other-side',
	null,
	[
		'category' => 'especiais'
	]
);

echo '<div style="margin-top:60px;">';


/** SECCAO - Fotografia + TITULO*/
get_template_part('inc/section_title', null, [
	'title' => 'Fotografia',
	'link' => '/category/fotografia/',
	'color' => '#000000' // ← dynamic color here!
]);

if (is_home() && is_front_page()) {
	echo '<div class="fotografia-main-banner">';
	do_action('digital_newspaper_main_banner_hook');
	echo '</div>';
}

echo '<div style="margin-top:60px;">';


/**
 * SECCAO PODCASTS
 * */

get_template_part(
	'template-parts/main-sections/home-podcasts-template',
	null,
	['category' => 'podcasts']
);

echo '<div style="margin-top:60px;">';


/**
 * videos
 */

get_template_part('inc/section_title', null, [
	'title' => 'Vídeos',
	'link' => '/category/videos/',
	'color' => '#000000', // ← dynamic color here!
	'view_all_text' => 'Ver todos',
]);

get_template_part(
	'template-parts/main-sections/home-sections-videos',
	null,
	[
		'category' => 'videos'
	]
);

echo '<div style="margin-top:60px;">';



get_footer();
