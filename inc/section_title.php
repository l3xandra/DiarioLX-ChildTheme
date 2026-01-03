<?php
$title = $args['title'] ?? '';
$link = $args['link'] ?? '';
$color = $args['color'] ?? '#49D3FF';
$font_size = $args['font_size'] ?? '20px'; // e.g. "28px", "2rem"
?>

<div class="digital-newspaper-container">
    <div class="row">
        <h2 class="digital-newspaper-block-title my-main-banner-title"
            style="--section-title-color: <?php echo esc_attr($color); ?>; --section-title-size: <?php echo esc_attr($font_size); ?>;">
            <span>
                <?php if (!empty($link)): ?>
                    <a href="<?php echo esc_url(home_url($link)); ?>">
                        <?php echo esc_html($title); ?>
                    </a>
                <?php else: ?>
                    <?php echo esc_html($title); ?>
                <?php endif; ?>
            </span>
        </h2>
    </div>
</div>
