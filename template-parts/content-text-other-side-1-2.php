<?php
/**
 * Template part for displaying posts (custom 1/3 text + 2/3 image)
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('post dlx-feature'); ?>>
  <div class="dlx-feature-row">
    <div class="dlx-feature-text">

      <?php digital_newspaper_get_post_categories(get_the_ID(), 0); ?>

      <h2 class="post-title t1C">
        <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
          <?php the_title(); ?>
        </a>
      </h2>

      <?php if (function_exists('dlx_render_post_authors')) {
        dlx_render_post_authors(get_the_ID());
      } ?>

    </div>

    <figure class="dlx-feature-figure <?php if (!has_post_thumbnail()) { echo esc_attr('no-feat-img'); } ?>">
      <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
        <?php
        if (has_post_thumbnail()) {
          the_post_thumbnail('digital-newspaper-list', array(
            'title' => the_title_attribute(array('echo' => false))
          ));
        }
        ?>
      </a>
    </figure>
  </div>
</article>
