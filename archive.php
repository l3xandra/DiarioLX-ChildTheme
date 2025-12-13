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
             * TÍTULO + COR
             */

            $title_section = 'inc/section-title-page';

            if ($categoria_slug === 'podcasts' || $categoria_slug === 'videos') {
                $title_section = 'inc/section-title-page-pods';
            }

            get_template_part(
                $title_section,
                null,
                [
                    'title' => $categoria_name,
                    'color' => $categoria_color,
                    'font_size' => '50px', // ajusta à vontade
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
                            while (have_posts()):
                                the_post();

                                // Usa o content-{post_type}.php
                                get_template_part('template-parts/content', get_post_type());
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