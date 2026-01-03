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

$category = $args['category'] ?? '';

/**
 * NOTICIA PRINCIPALL HOMEPAGE
 */
$top_post = new WP_Query([
    'posts_per_page' => 1,
    'post_type' => 'post',
    'post_status' => 'publish',
    'category_name' => $category
]);

if ($top_post->have_posts()):
    while ($top_post->have_posts()):
        $top_post->the_post();
        ?>
        <div class="digital-newspaper-container">
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
                            <div class="post-element-wrap">
                                <div class="post-element">

                                    <div class="dn-narrow-wrap">
                                        <div class="post-meta">
                                            <?php digital_newspaper_get_post_categories(get_the_ID(), 2); ?>
                                        </div>
                                        <h2 class="post-title">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </h2>
                                        <?php if (function_exists('dlx_render_post_authors')) {
                                            dlx_render_post_authors(get_the_ID());
                                        } ?>

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
