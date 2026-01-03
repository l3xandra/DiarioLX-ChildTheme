<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Digital Newspaper
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('post'); ?>>
    <div class="lca-blaze-box-row">
        <figure class="post-thumb-wrap <?php if (!has_post_thumbnail()) {
            echo esc_attr('no-feat-img');
        } ?>">
            <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                <?php
                if (has_post_thumbnail()) {
                    the_post_thumbnail('digital-newspaper-list', array(
                        'title' => the_title_attribute(array(
                            'echo' => false
                        ))
                    ));
                }
                ?>
            </a>
        </figure>

        <div class="post-element">

            <?php digital_newspaper_get_post_categories(get_the_ID(), 0); ?>

            <h2 class="post-title t1B"><a href="<?php the_permalink(); ?>"
                    title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
            </h2>
            <div class="post-excerpt main-post-home-lead"><?php the_excerpt(); ?></div>
            <?php if (function_exists('dlx_render_post_authors')) {
                dlx_render_post_authors(get_the_ID());
            } ?>

        </div>
    </div>
</article><!-- #post-<?php the_ID(); ?> -->
