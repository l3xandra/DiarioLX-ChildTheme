<?php
$title = $args['title'] ?? '';
$link = $args['link'] ?? '';
$color = $args['color'] ?? '#49D3FF';
$font_size = $args['font_size'] ?? '20px'; // e.g. "28px", "2rem"
$show_view_all = !empty($link) && is_home();
if (array_key_exists('show_view_all', $args)) {
    $show_view_all = (bool) $args['show_view_all'] && !empty($link) && is_home();
}
$view_all_text = $args['view_all_text'] ?? 'Ver todas';
$row_class = $args['row_class'] ?? '';
$span_class = $show_view_all ? 'section-title-row' : '';
if ($show_view_all && !empty($row_class)) {
    $span_class .= ' ' . $row_class;
}
?>

<div class="digital-newspaper-container">
    <div class="row">
        <h2 class="digital-newspaper-block-title my-main-banner-title"
            style="--section-title-color: <?php echo esc_attr($color); ?>; --section-title-size: <?php echo esc_attr($font_size); ?>;">
            <span<?php echo $span_class ? ' class="' . esc_attr($span_class) . '"' : ''; ?>>
                <?php if ($show_view_all): ?>
                    <a class="section-title-text" href="<?php echo esc_url(home_url($link)); ?>">
                        <?php echo esc_html($title); ?>
                    </a>
                    <a class="section-title-view-all" href="<?php echo esc_url(home_url($link)); ?>">
                        <?php echo esc_html($view_all_text); ?>
                    </a>
                <?php elseif (!empty($link)): ?>
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
