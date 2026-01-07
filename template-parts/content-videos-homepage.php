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
    <div class="blaze_box_wrap">
        <figure class="post-thumb-wrap video-thumb-wrap <?php if (!has_post_thumbnail()) {
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
            <?php digital_newspaper_get_post_categories(get_the_ID(), 0); ?>
            <?php if (has_post_thumbnail()) : ?>
                <span class="video-thumb-icon" aria-hidden="true">
                    <img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/icons/video_icon.svg'); ?>" alt="" />
                </span>
            <?php endif; ?>
        </figure>
        <div class="post-element">



            <h2 class="post-title t2"><a href="<?php the_permalink(); ?>"
                    title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
            
        </div>
    </div>
</article><!-- #post-<?php the_ID(); ?> -->
