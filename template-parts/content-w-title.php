<?php
/**
 * Template part for displaying posts WITH OPTIONAL CUSTOM TITLE
 */

$custom_title = $args['custom_title'] ?? '';
$custom_link  = $args['custom_link']  ?? '';
$custom_color = $args['custom_color'] ?? '#49D3FF';
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('post'); ?>>
    <div class="blaze_box_wrap">

        <?php
        // Load section title ABOVE the article
        if (!empty($args['custom_section'])) {
            $section = $args['custom_section'];

            get_template_part('inc/section_title_smaller', null, [
                'title' => $section['title'] ?? '',
                'link'  => $section['link']  ?? '',
                'color' => $section['color'] ?? '#000000'
            ]);
        }
        ?>

        <div style="margin-bottom: 12px;"></div>

        <figure class="post-thumb-wrap <?php if (!has_post_thumbnail()) { echo 'no-feat-img'; } ?>">
            <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                <?php
                if (has_post_thumbnail()) {
                    the_post_thumbnail('digital-newspaper-list', [
                        'title' => the_title_attribute(['echo' => false])
                    ]);
                }
                ?>
            </a>
            <?php digital_newspaper_get_post_categories(get_the_ID(), 0); ?>
        </figure>

        <div class="post-element">
            <h2 class="post-title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h2>
            <?php if (function_exists('dlx_render_post_authors')) {
                dlx_render_post_authors(get_the_ID());
            } ?>

            <div class="post-meta">
            </div>
        </div>

    </div>
</article>
