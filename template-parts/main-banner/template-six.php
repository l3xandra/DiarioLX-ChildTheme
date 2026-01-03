<?php
/**
 * Main Banner template six
 * 
 * @package Digital Newspaper
 * @since 1.0.0
 */
use Digital_Newspaper\CustomizerDefault as DN;

$slider_args = $args['slider_args'];
?>
<div class="main-banner-wrap">
    <div class="main-banner-slider">
        <?php
        $slider_args = apply_filters('digital_newspaper_query_args_filter', $slider_args);
        $slider_query = new WP_Query([
            'posts_per_page' => 4,
            'post_type' => 'post',
            'post_status' => 'publish',
            'orderby' => 'date',
            'order' => 'DESC',
            'category_name' => 'fotografia'
        ]);

        if ($slider_query->have_posts()):
            while ($slider_query->have_posts()):
                $slider_query->the_post();
                ?>
                <article class="slide-item <?php if (!has_post_thumbnail()) {
                    echo esc_attr('no-feat-img');
                } ?>">
                    <figure class="post-thumb">
                        <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                            <?php
                            if (has_post_thumbnail()) {
                                the_post_thumbnail('digital-newspaper-featured', array(
                                    'title' => the_title_attribute(array(
                                        'echo' => false
                                    ))
                                ));
                            }
                            ?>
                        </a>
                    </figure>
                    <?php /** TEXTO DA NOTICIA PRINCIPAL */ ?>
                    <div class="post-element-wrap">
                    <div class="post-element banner-six-info">
                            <div class="dn-narrow-wrap">
                                <div class="post-meta">
                                    <?php digital_newspaper_get_post_categories(get_the_ID(), 2); ?>
                                </div>
                                <h2 class="post-title white-font"><a href="<?php the_permalink(); ?>"
                                        title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
                                <?php if (function_exists('dlx_render_post_authors')) {
                                    dlx_render_post_authors(get_the_ID());
                                } ?>
                            </div>
                        </div>
                    </div>
                </article>
                <?php
            endwhile;
            wp_reset_postdata();
        endif;
        ?>
    </div>
</div>
