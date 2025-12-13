<?php
$title = $args['title'] ?? '';
$link  = $args['link']  ?? '';
$color = $args['color'] ?? '#49D3FF';
?>

<h2 class="digital-newspaper-block-title my-main-banner-title"
    style="
        border-bottom: 2px solid <?php echo esc_attr($color); ?> !important;
        padding-bottom: 4px;
        margin-bottom: 12px;
        width: 100%;
        display: block;
    ">
    
    <span style="color: <?php echo esc_attr($color); ?> !important;">
        
        <?php if (!empty($link)): ?>
            <a href="<?php echo esc_url(home_url($link)); ?>"
               style="color: inherit; text-decoration: none;">
                <?php echo esc_html($title); ?>
            </a>
        <?php else: ?>
            <?php echo esc_html($title); ?>
        <?php endif; ?>

    </span>

</h2>
