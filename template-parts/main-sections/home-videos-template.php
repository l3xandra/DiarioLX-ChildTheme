<?php
/**
 * Custom Bottom Full Width Section (with category override)
 */

use Digital_Newspaper\CustomizerDefault as DN;

// ----- READ ARGUMENTS -----
$forced_category = $args['category'] ?? ''; // ← dynamic category

// Load block settings from Customizer
$blocks = DN\digital_newspaper_get_customizer_option('bottom_full_width_blocks');
if (empty($blocks)) return;

$blocks = json_decode($blocks);
if (!in_array(true, array_column($blocks, 'option'))) return;

$width = digial_newspaper_get_section_width_layout_val('bottom_full_width_blocks_width_layout');
?>

<section id="bottom-full-width-section"
         class="digital-newspaper-section bottom-full-width-section <?php echo esc_attr('width-' . $width); ?>">

    <div class="digital-newspaper-container">
        <div class="row">

            <?php foreach ($blocks as $block) :
                if (!$block->option) continue;

                $type = $block->type;

                switch ($type) {

                    case 'shortcode-block':
                        digital_newspaper_shortcode_block_html($block, true);
                        break;

                    case 'ad-block':
                        digital_newspaper_advertisement_block_html($block, true);
                        break;

                    default:

                        $layout          = $block->layout;
                        $order           = $block->query->order;
                        $orderArray      = explode('-', $order);

                        // Base args
                        $post_args = [
                            'post_type'           => 'post',
                            'orderby'             => esc_html($orderArray[0]),
                            'order'               => esc_html($orderArray[1]),
                            'ignore_sticky_posts' => true
                        ];

                        // If CATEGORY WAS PASSED → OVERRIDE EVERYTHING
                        if (!empty($forced_category)) {

                            $post_args['posts_per_page'] = absint($block->query->count);
                            $post_args['category_name']  = $forced_category;

                        } else {

                            // Default theme behavior
                            $postCategories = $block->query->categories;
                            $excludeIDs     = $block->query->ids;

                            if ($block->query->postFilter == 'category') {

                                $post_args['posts_per_page'] = absint($block->query->count);

                                if ($excludeIDs)
                                    $post_args['post__not_in'] = $excludeIDs;

                                if ($postCategories)
                                    $post_args['category_name'] = digital_newspaper_get_categories_for_args($postCategories);

                                if ($block->query->dateFilter != 'all')
                                    $post_args['date_query'] =
                                        digital_newspaper_get_date_format_array_args($block->query->dateFilter);

                            } elseif ($block->query->postFilter == 'title') {

                                if ($block->query->posts)
                                    $post_args['post_name__in'] =
                                        digital_newspaper_get_post_slugs_for_args($block->query->posts);
                            }
                        }

                        // Send args to template
                        get_template_part(
                            'template-parts/' . esc_html($type) . '/template',
                            'videos',
                            [
                                'post_args' => $post_args,
                                'options'   => $block
                            ]
                        );
                        break;
                }
            endforeach; ?>

        </div>
    </div>
</section>
