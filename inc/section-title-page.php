<?php
$title = $args['title'] ?? '';
$link  = $args['link']  ?? '';
$color = $args['color'] ?? '#49D3FF'; // fallback default color
$font_size = $args['font_size'] ?? ''; // e.g. "1.4rem" or "20px"
?>

<div class="digital-newspaper-container">
    <div class="row">
        <h3 class="digital-newspaper-block-title my-main-banner-title"
            style="--section-title-color: <?php echo esc_attr($color); ?>;">
            <span>

                <?php if (!empty($link)): ?>
                    <a href="<?php echo esc_url( home_url( $link ) ); ?>">
                        <?php echo esc_html( $title ); ?>
                    </a>
                <?php else: ?>
                    <?php echo esc_html( $title ); ?>
                <?php endif; ?>

            </span>
        </h3>
    </div>
</div>
