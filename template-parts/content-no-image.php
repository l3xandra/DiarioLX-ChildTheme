<?php
/**
 * Template part for displaying posts (NO IMAGE)
 *
 * @package Digital Newspaper
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('post no-margin'); ?>>
    <div class="blaze_box_wrap no-padding">

        <!-- Removed entire <figure> thumbnail section -->

        <div class="post-element no-padding">

            <?php
            // Show category if you still want it
            digital_newspaper_get_post_categories(get_the_ID(), 0);
            ?>

            <h2 class="post-title smaller-tittle title-no-image">
                <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                    <?php the_title(); ?>
                </a>
            </h2>

            <div class="post-meta">
            </div>

        </div>

    </div>
</article>
