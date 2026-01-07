<?php
// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;


/***
 * DAY NIGHT MODE REMOVE
 */
// Disable the theme's header night-mode toggle (blaze-switcher-button)
if ( ! function_exists( 'digital_newspaper_header_theme_mode_icon_part' ) ) {
  function digital_newspaper_header_theme_mode_icon_part() {
    // do nothing
  }
}



// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if (!function_exists('chld_thm_cfg_locale_css')):
    function chld_thm_cfg_locale_css($uri)
    {
        if (empty($uri) && is_rtl() && file_exists(get_template_directory() . '/rtl.css'))
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter('locale_stylesheet_uri', 'chld_thm_cfg_locale_css');

if (!function_exists('chld_thm_cfg_parent_css')):
    function chld_thm_cfg_parent_css()
    {
        wp_enqueue_style('chld_thm_cfg_parent', trailingslashit(get_template_directory_uri()) . 'style.css', array('fontawesome', 'slick'));
    }
endif;
add_action('wp_enqueue_scripts', 'chld_thm_cfg_parent_css', 10);



/**
 * CATEGORY COLOR HOVER (TITLE + EXCERPT)
 */
if ( ! function_exists( 'dlx_enqueue_category_hover_script' ) ) {
    function dlx_enqueue_category_hover_script() {
        $src = get_stylesheet_directory_uri() . '/assets/js/category-hover-color.js';
        wp_enqueue_script( 'dlx-category-hover', $src, array(), '1.0.0', true );
    }
}
add_action( 'wp_enqueue_scripts', 'dlx_enqueue_category_hover_script', 20 );

/**
 * AUTO COLLAPSE MENU WHEN IT WRAPS
 */
if ( ! function_exists( 'dlx_enqueue_menu_collapse_script' ) ) {
    function dlx_enqueue_menu_collapse_script() {
        $src = get_stylesheet_directory_uri() . '/assets/js/menu-collapse-on-wrap.js';
        wp_enqueue_script( 'dlx-menu-collapse', $src, array(), '1.0.0', true );
    }
}
add_action( 'wp_enqueue_scripts', 'dlx_enqueue_menu_collapse_script', 25 );

// END ENQUEUE PARENT ACTION


/**
 * MAIN ARTICLE META BOX
 */
function escs_add_main_article_metabox() {
    add_meta_box(
        'escs-main-article',
        __( 'Artigo Principal', 'digital-newspaper-child' ),
        'escs_render_main_article_metabox',
        'post',
        'side',
        'high'
    );
}
add_action( 'add_meta_boxes', 'escs_add_main_article_metabox' );

function escs_render_main_article_metabox( $post ) {
    wp_nonce_field( 'escs_main_article_meta', 'escs_main_article_meta_nonce' );
    $is_main = ( get_post_meta( $post->ID, '_escs_is_main_article', true ) === 'yes' );
    echo '<label>';
    echo '<input type="checkbox" name="escs_main_article" value="yes" ' . checked( $is_main, true, false ) . ' /> ';
    echo esc_html__( 'Marcar como artigo principal', 'digital-newspaper-child' );
    echo '</label>';
}

function escs_save_main_article_meta( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( wp_is_post_revision( $post_id ) ) {
        return;
    }

    if ( ! isset( $_POST['escs_main_article_meta_nonce'] ) ||
        ! wp_verify_nonce( $_POST['escs_main_article_meta_nonce'], 'escs_main_article_meta' ) ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    $is_checked = ( isset( $_POST['escs_main_article'] ) && $_POST['escs_main_article'] === 'yes' );

    static $updating = false;
    if ( $updating ) {
        return;
    }
    $updating = true;

    if ( $is_checked ) {
        update_post_meta( $post_id, '_escs_is_main_article', 'yes' );

        $other_ids = get_posts( [
            'post_type'      => 'post',
            'post_status'    => 'any',
            'posts_per_page' => -1,
            'post__not_in'   => [ $post_id ],
            'fields'         => 'ids',
            'meta_key'       => '_escs_is_main_article',
            'meta_value'     => 'yes',
        ] );

        foreach ( $other_ids as $other_id ) {
            delete_post_meta( $other_id, '_escs_is_main_article' );
        }
    } else {
        delete_post_meta( $post_id, '_escs_is_main_article' );
    }

    $updating = false;
}
add_action( 'save_post', 'escs_save_main_article_meta' );



/**
 * ADD TITLE TO LISBOA, CIDADE ABERTA
 */
add_action('digital_newspaper_main_banner_hook', function () {
}, 10);



/**
 * DATA
 * Remover o "ago" do timestamp
 */
add_filter('digital_newspaper_inherit_published_date', function ($date) {
    // Remove a palavra 'ago' se estiver presente
    $date = str_ireplace('ago', '', $date);
    return trim($date);
});

// Permitir itálico em títulos usando *palavra*
add_filter('the_title', function ($title, $id) {

    // impedir que apareça HTML no painel
    if (is_admin())
        return $title;

    // encontra texto entre *asteriscos* e transforma em <em>
    $title = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $title);

    return $title;

}, 10, 2);


/**
 * CONTRAST OF TAG
 */
if ( ! function_exists( 'digital_newspaper_get_contrast_color' ) ) {
    function digital_newspaper_get_contrast_color( $hexcolor ) {
        $hexcolor = ltrim( $hexcolor, '#' );

        // Short hex (#abc)
        if ( strlen( $hexcolor ) === 3 ) {
            $r = hexdec( str_repeat( substr( $hexcolor, 0, 1 ), 2 ) );
            $g = hexdec( str_repeat( substr( $hexcolor, 1, 1 ), 2 ) );
            $b = hexdec( str_repeat( substr( $hexcolor, 2, 1 ), 2 ) );
        } else {
            $r = hexdec( substr( $hexcolor, 0, 2 ) );
            $g = hexdec( substr( $hexcolor, 2, 2 ) );
            $b = hexdec( substr( $hexcolor, 4, 2 ) );
        }

        // YIQ – decide if background is light or dark
        $yiq = ( ( $r * 299 ) + ( $g * 587 ) + ( $b * 114 ) ) / 1000;

        // light bg → black text, dark bg → white text
        return ( $yiq >= 128 ) ? '#000000' : '#ffffff';
    }
}



/***
 * TAGS em vez de CATEGORIAS
 */
if (!function_exists('digital_newspaper_get_post_categories')):
    /**
     * Show first tag but using the category color
     */
    function digital_newspaper_get_post_categories($post_id, $number)
    {

        // Get FIRST CATEGORY (for class only)
        $categories      = get_the_category($post_id);
        $cat_color_class = '';

        if (!empty($categories)) {
            $main_cat        = $categories[0];
            $cat_color_class = 'cat-' . $main_cat->term_id; // bg continua a vir do CSS
        }

        // FIRST TAG (for text)
        $tags = get_the_tags($post_id);

        if ($tags && !is_wp_error($tags)) {
            $first_tag = $tags[0];

            $force_dark_tag = has_category(array('podcasts', 'videos'), $post_id);
            $tag_text_color = $force_dark_tag ? '#171717' : '#ffffff';
            $a_style = ' style="color:' . esc_attr($tag_text_color) . ' !important;"';

            echo '<ul class="post-categories">';
            echo '<li class="cat-item ' . esc_attr($cat_color_class) . '">'; // sem inline background
            echo '<a href="' . esc_url(get_tag_link($first_tag->term_id)) . '" rel="tag"' . $a_style . '>';
            echo esc_html($first_tag->name);
            echo '</a>';
            echo '</li>';
            echo '</ul>';
        }
    }
endif;




/***
 * GET CATEGORY COLOR
 */
/**
 * Get the CSS color assigned to a category (Digital Newspaper theme)
 */
use Digital_Newspaper\CustomizerDefault as DN;

function dlx_get_category_color($cat_id)
{

    // Get the stored category color entry
    $color_data = DN\digital_newspaper_get_customizer_option(
        'category_' . absint($cat_id) . '_color'
    );

    echo 'color_data = ' . $color_data[0];

    if (!is_array($color_data) || empty($color_data['color'])) {
        return false;
    }

    $raw = $color_data['color']; // might be CSS variable

    // Convert variable → hex
    $resolved = dlx_resolve_global_color_var($raw);

    // Return the hex or fallback
    return $resolved ?: false;
}




function dlx_resolve_css_var_to_hex($css_var)
{
    if (empty($css_var))
        return false;

    // Only process CSS variables
    if (strpos($css_var, '--') !== 0)
        return $css_var;

    // Find the variable inside all registered styles
    global $wp_styles;

    foreach ($wp_styles->registered as $style) {
        $src = $style->src;

        // Only local files
        if (strpos($src, home_url()) === 0 || strpos($src, '/') === 0) {

            $path = str_replace(home_url(), ABSPATH, $src);

            if (file_exists($path)) {
                $css = file_get_contents($path);

                // Match: --var-name: #hex;
                $pattern = '/' . preg_quote($css_var, '/') . '\s*:\s*([^;]+);/';
                echo 'pattern = ' . $pattern . ' | ';
                echo 'preg = ' . preg_match($pattern, $css, $matches);
                if (preg_match($pattern, $css, $matches)) {
                    return trim($matches[1]);  // returns "#49D3FF"
                }
            }
        }
    }

    return false;
}


function dlx_resolve_global_color_var($var)
{

    // Only process CSS variables
    if (strpos($var, '--digital-newspaper-global-preset-color-') !== 0) {
        return $var; // already a hex or invalid
    }

    // Extract number from --digital-newspaper-global-preset-color-7
    if (preg_match('/color-(\d+)/', $var, $m)) {
        $index = intval($m[1]);

        // Get the actual hex from Customizer global colors
        $hex = get_theme_mod("digital_newspaper_global_color_{$index}");

        if ($hex)
            return $hex;
    }

    return false;
}


/***
 * AUTORES VARIOS
 */

if (!function_exists('digital_newspaper_posted_by')):
    /**
     * Prints HTML with meta information for the current author(s).
     * Supports PublishPress Authors if available.
     */
    function digital_newspaper_posted_by($post_id = '')
    {

        // Try PublishPress Authors first (multiple authors / guest authors)
        if (function_exists('get_multiple_authors')) {

            // If a post ID was passed, use that; otherwise let it use the global post.
            $post_for_authors = $post_id ? $post_id : null;

            $authors = get_multiple_authors($post_for_authors);

            if (!empty($authors) && is_array($authors)) {
                $author_links = array();

                foreach ($authors as $author) {
                    // Each $author is an instance of MultipleAuthors\Classes\Objects\Author
                    $author_links[] = sprintf(
                        '<span class="author vcard"><a class="url fn n author_name" href="%s">%s</a></span>',
                        esc_url($author->link),
                        esc_html($author->display_name)
                    );
                }

echo '<span class="byline"> ' . implode(',&nbsp;', $author_links) . ' <span class="byline-sep"> &nbsp &nbsp • &nbsp &nbsp </span></span>';
                return;
            }
        }

        // Fallback: default single WP author (original behavior)
        $author_id = $post_id ? get_post_field('post_author', $post_id) : get_the_author_meta('ID');
        $author_name = $post_id ? get_the_author_meta('display_name', $author_id) : get_the_author();

        $byline = sprintf(
            '<span class="author vcard"><a class="url fn n author_name" href="%s">%s</a></span>',
            esc_url(get_author_posts_url($author_id)),
            esc_html($author_name)
        );

        echo '<span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
endif;

/**
 * Render authors row like single.php (POR + authors list).
 */
if ( ! function_exists( 'dlx_render_post_authors' ) ) :
    function dlx_render_post_authors( $post_id = 0 ) {
        $post_id = $post_id ? (int) $post_id : get_the_ID();
        if ( ! $post_id || 'post' !== get_post_type( $post_id ) ) {
            return;
        }
        $author_links = array();

        // PublishPress Authors (multiple authors / guest authors).
        if ( function_exists( 'get_multiple_authors' ) ) {
            $authors = get_multiple_authors( $post_id );
            if ( ! empty( $authors ) && is_array( $authors ) ) {
                foreach ( $authors as $author ) {
                    $author_links[] = sprintf(
                        '<span class="author vcard"><a class="url fn n author_name" href="%s">%s</a></span>',
                        esc_url( $author->link ),
                        esc_html( $author->display_name )
                    );
                }
            }
        }

        // Fallback: default single WP author.
        if ( empty( $author_links ) ) {
            $author_id = (int) get_post_field( 'post_author', $post_id );
            $author_links[] = sprintf(
                '<span class="author vcard"><a class="url fn n author_name" href="%s">%s</a></span>',
                esc_url( get_author_posts_url( $author_id ) ),
                esc_html( get_the_author_meta( 'display_name', $author_id ) )
            );
        }

        echo '<div class="single-authors-row dlx-content-authors">';
        echo '<div class="single-authors-list"><span class="byline">' . implode( ',&nbsp;', $author_links ) . '</span></div>';
        echo '</div>';
    }
endif;


/***
 * FOTOGRAFO
 */
/**
 * Meta box: Fotógrafo (seleciona um utilizador)
 */
function dlx_add_photo_user_metabox()
{
    add_meta_box(
        'dlx_photo_user',
        'Fotógrafo',
        'dlx_photo_user_metabox_callback',
        'post',   // só para posts
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'dlx_add_photo_user_metabox');

function dlx_photo_user_metabox_callback($post)
{
    // Segurança
    wp_nonce_field('dlx_save_photo_user', 'dlx_photo_user_nonce');

    $selected_user = get_post_meta($post->ID, '_dlx_photo_user', true);

    wp_dropdown_users(array(
        'name' => 'dlx_photo_user',
        'selected' => $selected_user,
        'show_option_none' => '— Nenhum fotógrafo —',
        'include_selected' => true,
        // Se quiseres limitar: 'role__in' => array( 'author', 'editor' ),
    ));
}

function dlx_save_photo_user_metabox($post_id)
{
    // Verifica nonce
    if (
        !isset($_POST['dlx_photo_user_nonce']) ||
        !wp_verify_nonce($_POST['dlx_photo_user_nonce'], 'dlx_save_photo_user')
    ) {
        return;
    }

    // Evita autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Permissões
    if (isset($_POST['post_type']) && 'post' === $_POST['post_type']) {
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
    }

    if (isset($_POST['dlx_photo_user']) && $_POST['dlx_photo_user'] !== '') {
        $photo_user_id = (int) $_POST['dlx_photo_user'];
        update_post_meta($post_id, '_dlx_photo_user', $photo_user_id);
    } else {
        delete_post_meta($post_id, '_dlx_photo_user');
    }
}
add_action('save_post', 'dlx_save_photo_user_metabox');


/**
 * TAMBEM PODES GOSTAR
 */

if ( ! function_exists( 'digital_newspaper_single_related_posts' ) ) :
    /**
     * Single related posts – styled like home sections
     */
    function digital_newspaper_single_related_posts() {

        // Only for posts
        if ( get_post_type() !== 'post' ) {
            return;
        }

        // Respect customizer toggle
        $single_post_related_posts_option = DN\digital_newspaper_get_customizer_option( 'single_post_related_posts_option' );
        if ( ! $single_post_related_posts_option ) {
            return;
        }

        // Get current post categories
        $current_post_categories = get_the_category( get_the_ID() );
        $query_cats              = [];

        if ( $current_post_categories ) {
            foreach ( $current_post_categories as $current_post_cat ) {
                $query_cats[] = (int) $current_post_cat->term_id;
            }
        }

        // Build related posts query (4 posts now)
        $related_posts_args = [
            'posts_per_page'      => 4,                  // ← 4 posts
            'post__not_in'        => [ get_the_ID() ],
            'ignore_sticky_posts' => true,
        ];

        if ( ! empty( $query_cats ) ) {
            $related_posts_args['category__in'] = $query_cats;
        }

        // Keep your existing filter hook
        $related_posts_args = apply_filters( 'digital_newspaper_query_args_filter', $related_posts_args );

        $related_posts = new WP_Query( $related_posts_args );

        if ( ! $related_posts->have_posts() ) {
            return;
        }

        // Title from Customizer (fallback if empty)
        $related_posts_title = DN\digital_newspaper_get_customizer_option( 'single_post_related_posts_title' );
        if ( ! $related_posts_title ) {
            $related_posts_title = __( 'Artigos Relacionados', 'digital-newspaper' );
        }

        // Build link & color for section_title
        $cat_link = '';
        $color    = '#000000'; // default – change to your dynamic color if you have that function

        if ( ! empty( $current_post_categories ) ) {
            $primary_cat = $current_post_categories[0];
            $cat_link    = get_category_link( $primary_cat->term_id );

            // If you have a helper to get category color, plug it here:
            // $color = digital_newspaper_get_category_color( $primary_cat->term_id );
        }
        ?>

        <section class="single-related-posts-section related-from-home-section">
            <?php
            // Same style as your home section title
            get_template_part(
                'inc/section_title',
                null,
                [
                    'title' => $related_posts_title,
                    'link'  => $cat_link, // e.g. to the main category archive
                    'color' => $color,
                ]
            );

            // Use the new template that behaves like home-sections-template
            get_template_part(
                'template-parts/main-sections/related-posts-template',
                null,
                [
                    'query' => $related_posts,
                ]
            );
            ?>
        </section>

        <?php
    }
endif;
add_action('digital_newspaper_single_post_append_hook', 'digital_newspaper_single_related_posts');

/***
 * TAGS NO SINGLE POST
 */
if( ! function_exists( 'digital_newspaper_tags_list' ) ) :
	/**
	 * print the html for tags list
	 */
	function digital_newspaper_tags_list() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', ' ' );
			if ( $tags_list ) {
				/* translators: 1: list of tags. */
				printf( '<span class="tags-links">' . esc_html__( '%1$s', 'digital-newspaper' ) . '</span>', $tags_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}
	}
endif;


/**
 * FOOTER
 */
if( ! function_exists( 'digital_newspaper_bottom_footer_copyright_part' ) ) :
   /**
    * Bottom Footer copyright element
    * 
    * @since 1.0.0
    */
   function digital_newspaper_bottom_footer_copyright_part() {
      $bottom_footer_site_info = DN\digital_newspaper_get_customizer_option( 'bottom_footer_site_info' );
      if( ! $bottom_footer_site_info ) return;
     ?>
        <div class="site-info <?php if( !DN\digital_newspaper_get_customizer_option( 'bottom_footer_menu_option' ) ) echo esc_attr(' blaze_copyright_align_center');  ?>">
            <?php echo wp_kses_post( str_replace( '%year%', date('Y'), $bottom_footer_site_info ) ); ?>
        </div>
     <?php
   }
   add_action( 'digital_newspaper_botttom_footer_hook', 'digital_newspaper_bottom_footer_copyright_part', 20 );
endif;

/**
 * TIPOS DIFERENTES DE TEXTO - ARTICLE
 */
/**
 * Override editor font sizes (Gutenberg: S / M / L / XL buttons).
 */
function dn_child_custom_editor_font_sizes() {

    // Define your own sizes (change names, shortName and size as you like)
    add_theme_support(
        'editor-font-sizes',
        array(
            array(
                'name'      => __( 'Legenda', 'digital-newspaper-child' ),
                'shortName' => 'L', // what appears on the button
                'size'      => 10,
                'weight' => '400',
                'slug'      => 'legenda',
            ),
            array(
                'name'      => __( 'Corpo de texto', 'digital-newspaper-child' ),
                'shortName' => 'CT',
                'size'      => 15,
                'weight' => '400',
                'slug'      => 'corpo-texto',
            ),
            array(
                'name'      => __( 'Titulo', 'digital-newspaper-child' ),
                'shortName' => 'T',
                'size'      => 30,
                'weight' => '800',
                'slug'      => 'titulo',
            ),
            array(
                'name'      => __( 'Destaque', 'digital-newspaper-child' ),
                'shortName' => 'D',
                'size'      => 40,
                'weight' => '700',
                'slug'      => 'destaque',
            ),
        )
    );
}
add_action( 'after_setup_theme', 'dn_child_custom_editor_font_sizes', 11 );


/***
 * ESCONDER MAIN-POST
 */
/**
 * Esconde tags técnicas (ex: "main-post") da apresentação
 * e reindexa o array para que a "segunda" tag passe a ser a primeira.
 */
function dn_hide_technical_tags_from_display( $terms, $post_id, $taxonomy ) {
    if ( $taxonomy !== 'post_tag' || empty( $terms ) || is_wp_error( $terms ) ) {
        return $terms;
    }

    // Slugs das tags que são só para lógica, não para mostrar
    $hidden_slugs = array(
        'main-post',      // destaque principal
        // 'main-post-2', // podes adicionar mais se quiseres
    );

    $visible_terms = array();

    foreach ( $terms as $term ) {
        // Só mantemos as tags que NÃO são técnicas
        if ( ! in_array( $term->slug, $hidden_slugs, true ) ) {
            $visible_terms[] = $term; // reindexa automaticamente: 0,1,2,...
        }
    }

    return $visible_terms;
}
add_filter( 'get_the_terms', 'dn_hide_technical_tags_from_display', 10, 3 );


/***
 * LOGO ANIMADO
 */
/**
 * 1) Replace the Custom Logo markup with a dotLottie web component
 */
add_filter('get_custom_logo', function ($html, $blog_id) {

  $src  = get_stylesheet_directory_uri() . '/assets/lottie/LOGO_ANIM.lottie';
  $home = home_url('/');
  $name = get_bloginfo('name');

  return sprintf(
    '<a href="%s" class="custom-logo-link dlx-lottie-logo-link" rel="home" aria-label="%s">' .
      '<dotlottie-wc id="dlxHeaderLottie" class="custom-logo dlx-header-lottie" src="%s"></dotlottie-wc>' .
    '</a>',
    esc_url($home),
    esc_attr($name),
    esc_url($src)
  );
}, 10, 2);


/**
 * 2) Enqueue dotLottie player + your scroll controller
 */
add_action('wp_enqueue_scripts', function () {

  // dotlottie-wc is a MODULE script (web component)
  wp_enqueue_script(
    'dotlottie-wc',
    'https://unpkg.com/@lottiefiles/dotlottie-wc@latest/dist/dotlottie-wc.js',
    [],
    null,
    true
  );

  // Force type="module"
  add_filter('script_loader_tag', function ($tag, $handle, $src) {
    if ($handle === 'dotlottie-wc') {
      return '<script type="module" src="' . esc_url($src) . '"></script>';
    }
    return $tag;
  }, 10, 3);

  // your header scroll logic
  wp_enqueue_script(
    'dlx-header-lottie',
    get_stylesheet_directory_uri() . '/assets/js/header-lottie-logo.js',
    [],
    null,
    true
  );
});

/**
 * HOMEPAGE MENU TRANSICAO SCROLL
 */
add_action('wp_footer', function () {
  if ( ! ( is_front_page() || is_home() ) ) return;
  ?>
  <script>
  (function () {
    const b = document.body;
    const threshold = 10; // px before reverting

    let ticking = false;
    function update() {
      const scrolled = window.scrollY > threshold;
      b.classList.toggle('dlx-scrolled', scrolled);
      ticking = false;
    }

    update();
    window.addEventListener('scroll', function () {
      if (!ticking) {
        window.requestAnimationFrame(update);
        ticking = true;
      }
    }, { passive: true });
  })();
  </script>
  <?php
}, 99);

/**
 * DESTAQUE ARTIGO COR
 */







/**
 * 1) Vai buscar a cor do tema para uma categoria (a mesma que pinta a pill/tag).
 */
function dlx_get_category_color_from_theme( $cat_id ) {
    $cat_id = absint($cat_id);
    if (!$cat_id) return '';

    if (!function_exists('\Digital_Newspaper\CustomizerDefault\digital_newspaper_get_customizer_option')) {
        return '';
    }

    $data = \Digital_Newspaper\CustomizerDefault\digital_newspaper_get_customizer_option('category_' . $cat_id . '_color');

    if (!is_array($data) || empty($data['color'])) return '';

    $color = $data['color'];
    if (function_exists('digital_newspaper_get_color_format')) {
        $color = digital_newspaper_get_color_format($color);
    }
    return $color;
}

/**
 * 2) Escolhe a “categoria principal” (a 1ª) e devolve a cor.
 */
function dlx_get_post_accent_color( $post_id ) {
    $post_id = absint($post_id);
    if (!$post_id) return '';

    $cats = wp_get_post_categories($post_id, ['number' => 1]);
    if (empty($cats)) return '';

    return dlx_get_category_color_from_theme((int)$cats[0]);
}

/**
 * 3) FRONT-END (single post): define --dlx-cat-accent no <body>.
 */
add_action('wp_enqueue_scripts', function () {
    if (!is_singular('post')) return;

    $color = dlx_get_post_accent_color(get_queried_object_id());
    if (!$color) return;

    $handle = wp_style_is('digital-newspaper-child-style', 'enqueued')
        ? 'digital-newspaper-child-style'
        : 'digital-newspaper-style';

    wp_add_inline_style($handle, "body.single-post{--dlx-cat-accent: {$color};}");
}, 20);

/**
 * 4) EDITOR (Gutenberg): atualiza --dlx-cat-accent quando mudas a categoria no editor.
 */
add_action('enqueue_block_editor_assets', function () {

    // mapa term_id => cor
    $cats = get_categories(['hide_empty' => false]);
    $map  = [];

    foreach ($cats as $cat) {
        $c = dlx_get_category_color_from_theme($cat->term_id);
        if ($c) $map[(int)$cat->term_id] = $c;
    }

    // CSS no editor (podes usar em qualquer selector via var(--dlx-cat-accent))
    wp_register_style('dlx-editor-accent', false);
    wp_enqueue_style('dlx-editor-accent');
    wp_add_inline_style('dlx-editor-accent', ".editor-styles-wrapper{--dlx-cat-accent: transparent;}");

    // JS que lê a categoria escolhida e mete a var
    $json = wp_json_encode($map);
    $js = "window.DLX_CAT_COLORS={$json};
(function(){
  if(!window.wp || !wp.data) return;

  const target = document.querySelector('.editor-styles-wrapper') || document.documentElement;

  const apply = () => {
    const cats = wp.data.select('core/editor').getEditedPostAttribute('categories') || [];
    const id = cats[0];
    const col = (id && window.DLX_CAT_COLORS && window.DLX_CAT_COLORS[id]) ? window.DLX_CAT_COLORS[id] : '';
    if(col) target.style.setProperty('--dlx-cat-accent', col);
    else target.style.removeProperty('--dlx-cat-accent');
  };

  let last = null;
  apply();
  wp.data.subscribe(() => {
    const cats = wp.data.select('core/editor').getEditedPostAttribute('categories') || [];
    const now = cats[0] || null;
    if(now !== last){ last = now; apply(); }
  });
})();";

    wp_add_inline_script('wp-data', $js, 'after');
}, 20);


function dlx_get_theme_category_color($term_id, $fallback = '#49D3FF') {
  $data = DN\digital_newspaper_get_customizer_option('category_' . absint($term_id) . '_color');

  if (is_array($data) && !empty($data['color'])) {
    return function_exists('digital_newspaper_get_color_format')
      ? digital_newspaper_get_color_format($data['color'])
      : $data['color'];
  }

  return $fallback;
}



/**
 * FOOTER OVERRIDES
 * Replace the markup in the functions below to build a custom footer.
 */
function escs_override_footer_hooks() {
    remove_action( 'digital_newspaper_footer_hook', 'digital_newspaper_footer_widgets_area_part', 10 );

    // Ensure our bottom footer runs before the parent callbacks.
    add_action( 'digital_newspaper_botttom_footer_hook', 'escs_bottom_footer_override', 0 );

    add_action( 'digital_newspaper_footer_hook', 'escs_footer_main', 10 );
}
add_action( 'after_setup_theme', 'escs_override_footer_hooks', 20 );

function escs_footer_logo_block() {
    $logo_base = get_stylesheet_directory_uri() . '/assets/logos/';
    ?>
    <div class="footer-logo" style="text-align: center; margin-bottom: 20px;">
        <img src="<?php echo esc_url( $logo_base . 'LOGO_DLX_WHITE.png' ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" style="display: block; margin: 0 auto; max-width: 150px; width: 100%; height: auto; margin-top: 30px;">
        <div class="footer-social-row" style="display: flex; justify-content: center; align-items: center; gap: 16px; margin-top: 40px; padding-bottom: 30px; border-bottom: 0.5px solid #ffffff;">
            <a href="<?php echo esc_url( 'https://twitter.com/diariolx' ); ?>" target="_blank" rel="noopener noreferrer" aria-label="Twitter">
                <img src="<?php echo esc_url( $logo_base . 'twitter.svg' ); ?>" alt="Twitter" style="min-width: 30px; height: auto;">
            </a>
            <a href="<?php echo esc_url( 'https://instagram.com/diariolx' ); ?>" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                <img src="<?php echo esc_url( $logo_base . 'instagram.svg' ); ?>" alt="Instagram" style="min-width: 30px; height: auto;">
            </a>
            <a href="<?php echo esc_url( 'https://facebook.com/diariolx' ); ?>" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                <img src="<?php echo esc_url( $logo_base . 'facebook.svg' ); ?>" alt="Facebook" style="min-width: 30px; height: auto;">
            </a>
        </div>
        <div class="footer-logo-row" style="display: flex; justify-content: center; align-items: center; gap: 35px; flex-wrap: wrap; margin-top: 20px; padding-bottom: 20px; border-bottom: 0.5px solid #ffffff;">
            <span class="footer-logo-text footer-contact-text">diariolx@escs.ipl.pt<br>Campus de Benfica do IPL<br>1549-014 Lisboa</span>
            <img src="<?php echo esc_url( $logo_base . 'LOGO_LIACOM.svg' ); ?>" alt="LIACOM" style="max-height: 200px; width: auto; height: auto;">
            <span class="footer-logo-text">Laboratório de Tendências<br>em Jornalismo</span>
            <img src="<?php echo esc_url( $logo_base . 'LOGO_ESCS.svg' ); ?>" alt="ESCS" style="max-height: 200px; width: auto; height: auto;">
            <img src="<?php echo esc_url( $logo_base . 'logo_IPL.svg' ); ?>" alt="IPL" style="max-height: 200px; width: auto; height: auto;">
        </div>
        <div class="footer-copyright-row" style="margin-top: 20px; text-align: center; font-family: var(--font-family-base); font-size: 12px; color: #ffffff;">
            &copy; Copyright <?php echo esc_html( date( 'Y' ) ); ?> · DiárioLX
        </div>
    </div>
    <?php
}

function escs_footer_main() {
    ?>
    <div class="footer-widget column-one">
        <?php escs_footer_logo_block(); ?>
    </div>
    <?php
}

function escs_bottom_footer_override() {
    remove_action( 'digital_newspaper_botttom_footer_hook', 'digital_newspaper_botttom_footer_social_part', 10 );
    remove_action( 'digital_newspaper_botttom_footer_hook', 'digital_newspaper_bottom_footer_inner_wrapper_open', 15 );
    remove_action( 'digital_newspaper_botttom_footer_hook', 'digital_newspaper_bottom_footer_copyright_part', 20 );
    remove_action( 'digital_newspaper_botttom_footer_hook', 'digital_newspaper_bottom_footer_menu_part', 30 );
    remove_action( 'digital_newspaper_botttom_footer_hook', 'digital_newspaper_bottom_footer_inner_wrapper_close', 40 );

    ?>
    <div class="bottom-inner-wrapper">
        <?php escs_footer_logo_block(); ?>
        <?php
        // TODO: add your custom bottom footer markup here.
        ?>
    </div>
    <?php
}
