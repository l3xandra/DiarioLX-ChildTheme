<?php
use Digital_Newspaper\CustomizerDefault as DN;

// Use passed query args if available
$top_post_args = $args['slider_args'] ?? array(
    'posts_per_page' => 1,
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'tag'            => 'main-post'
);

$top_post_query = new WP_Query($top_post_args);

if ($top_post_query->have_posts()) : ?>
    <div class="main-banner-wrap">
        <div class="main-banner-slider">
            <?php while ($top_post_query->have_posts()) : $top_post_query->the_post(); ?>
                <article class="slide-item <?php if (!has_post_thumbnail()) echo 'no-feat-img'; ?>">
                    <figure class="post-thumb">
                        <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                            <?php 
                            if (has_post_thumbnail()) :
                                the_post_thumbnail('digital-newspaper-featured', [
                                    'title' => the_title_attribute(['echo' => false])
                                ]);
                            endif;
                            ?>
                        </a>
                    </figure>
                    <div class="post-element">
                        <div class="post-meta">
                            <?php digital_newspaper_get_post_categories(get_the_ID(), 2); ?>
                            
                        </div>
                        <h2 class="post-title">
                            <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
                        </h2>
                        <div class="post-excerpt"><?php the_excerpt(); ?></div>
                    </div>
                </article>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    </div>
<?php endif; ?>
