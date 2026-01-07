<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Digital Newspaper
 */

use Digital_Newspaper\CustomizerDefault as DN;

get_header();

/**
 * hook - digital_newspaper_before_inner_content
 */

if (have_posts()):

    /*
     * TÍTULO DA CATEGORIA
     * (para páginas de arquivo de categoria, tag, etc — excepto author)
     */
    if (!is_author()):

        // Objeto da categoria/termo actual
        $categoria = get_queried_object();

        if ($categoria && !is_wp_error($categoria) && !empty($categoria->term_id)) {

            // ID, nome e slug da categoria
            $categoria_id = $categoria->term_id;
            $categoria_name = $categoria->name;
            $categoria_slug = $categoria->slug;

            /**
             * COR DA CATEGORIA
             * Lê o mesmo valor que o tema usa para gerar as .cat-XX
             * Opção: category_{ID}_color
             * Estrutura típica: [ 'color' => '--digital-newspaper-global-preset-color-7', 'hover' => '...' ]
             */
            $categoria_color_data = DN\digital_newspaper_get_customizer_option(
                'category_' . absint($categoria_id) . '_color'
            );

            // Fallback
            $categoria_color = '#000000';

            if (is_array($categoria_color_data) && !empty($categoria_color_data['color'])) {
                if (function_exists('digital_newspaper_get_color_format')) {
                    $categoria_color = digital_newspaper_get_color_format($categoria_color_data['color']);
                } else {
                    $categoria_color = $categoria_color_data['color'];
                }
            }

            /**
             * TAG: usa a cor da 1a categoria do 1o post do tag
             */
            if (is_tag()) {
                global $wp_query;
                $first_post = $wp_query->posts[0] ?? null;

                if ($first_post && !empty($first_post->ID)) {
                    $post_cats = get_the_category($first_post->ID);

                    if (!empty($post_cats) && !is_wp_error($post_cats)) {
                        $primary_cat = $post_cats[0];
                        $categoria_color = dlx_get_theme_category_color($primary_cat->term_id, $categoria_color);
                    }
                }
            }

            /**
             * TÍTULO + COR
             */

            $title_text = $categoria_name;
            $title_font_size = '40px';
            $title_font_weight = '500';

            if (is_tag()) {
                $title_text = 'Tag: ' . $categoria_name;
                $title_font_size = '20px';
                $title_font_weight = '600';
            }

            $title_section = 'inc/section-title-page';
            $title_icon = '';
            $title_icon_alt = '';

            if ($categoria_slug === 'podcasts' || $categoria_slug === 'videos') {
                $title_section = 'inc/section-title-page-pods';
            }

            if ($categoria_slug === 'podcasts') {
                $title_icon = get_stylesheet_directory_uri() . '/assets/icons/podcast_icon.svg';
                $title_icon_alt = 'Podcast';
            }
            if ($categoria_slug === 'videos') {
                $title_icon = get_stylesheet_directory_uri() . '/assets/icons/video_icon.svg';
                $title_icon_alt = 'Video';
            }

            get_template_part(
                $title_section,
                null,
                [
                    'title' => $title_text,
                    'color' => $categoria_color,
                    'font_size' => $title_font_size,
                    'font_weight' => $title_font_weight,
                    'icon' => $title_icon,
                    'icon_alt' => $title_icon_alt,
                ]
            );


            /**
             * HEADER COM A NOTICIA EM DESTAQUE
             * Escolhe o template conforme a categoria
             */

            // Escolher qual template usar
            $banner_template = 'template-parts/main-banner/template-six-banner';

            if ($categoria_slug === 'podcasts' || $categoria_slug === 'videos') {
                $banner_template = 'template-parts/main-banner/template-six-banner-pods';
            }

            // Carregar o banner correcto
            get_template_part(
                $banner_template,
                null,
                [
                    'category' => $categoria_slug, // passa o slug da categoria para o template
                ]
            );
        }

    endif;
    ?>

    <!-- GRID DE ARTIGOS -->
    <div id="theme-content">
        <?php
        /**
         * hook - digital_newspaper_before_main_content
         */
        do_action('digital_newspaper_before_main_content');
        ?>

        <main id="primary"
            class="site-main <?php echo esc_attr('width-' . digial_newspaper_get_section_width_layout_val()); ?>">

            <div class="digital-newspaper-container">
                <div class="row">
                    <div class="primary-content">
                        <div class="post-inner-wrapper news-list-wrap">
                            <?php
                            // Loop de posts
                            $archive_index = 0;
                            while (have_posts()):
                                the_post();

                                if (is_category() && 0 === $archive_index) {
                                    $archive_index++;
                                    continue;
                                }

                                // Usa o content-{post_type}.php
                                get_template_part('template-parts/content', get_post_type());

                                $archive_index++;
                            endwhile;

                            /**
                             * Paginação
                             */
                            do_action('digital_newspaper_pagination_link_hook');
                            ?>
                        </div>
                    </div>
                </div>
            </div>

        </main><!-- #main -->
    </div><!-- #theme-content -->

    <?php
else:

    // Sem posts
    get_template_part('template-parts/content', 'none');

endif;

get_footer();
