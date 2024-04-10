<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

/**
 * Define the "Banner" Post Type functionality.
 *
 * Loads and defines the Post Type called "Banner".
 *
 * @link       https://scoprinetwork.com
 * @since      1.0.0
 * 
 * @package    Banner
 * @subpackage Banner/includes/post_types
 * @author     Fabio Maulucci <fabio.maulucci@loscoprinetwork.it>
 */
class Banner_PT_Banner
{


    /**
     * Load the post type.
     *
     * @since    1.0.0
     */
    public function load_pt()
    {

        $labels = array(
            'name'                  => __('Banner', 'banner'),
            'singular_name'         => __('Convenzione', 'banner'),
            'menu_name'             => __('Banner', 'banner'),
            'name_admin_bar'        => __('Banner', 'banner'),
            'add_new'               => __('Aggiungi banner', 'banner'),
            'add_new_item'          => __('Aggiungi nuovo banner', 'banner'),
            'new_item'              => __('Nuovo banner', 'banner'),
            'edit_item'             => __('Modifica banner', 'banner'),
            'view_item'             => __('Vedi banner', 'banner'),
            'all_items'             => __('Lista dei banner', 'banner'),
            'search_items'          => __('Cerca banner', 'banner'),
            'not_found'             => __('Banner non trovato', 'banner'),
            'featured_image'        => __('Immagine del banner', 'banner'),
            'set_featured_image'    => __('Imposta immagine', 'banner'),
            'remove_featured_image' => __('Rimuove immagine', 'banner'),
            'use_featured_image'    => __('Utilizza immagine', 'banner'),
            'archives'              => __('Archivio dei banner', 'banner'),
        );

        $args = array(
            'labels'                => $labels,
            'public'                => true,
            //'publicly_queryable'    => false,
            'has_archive'           => false,
            'hierarchical'          => false,
            'rewrite'               => array('slug' => 'banner'),
            'has_archive'           => true,
            'menu_icon'              => 'dashicons-images-alt2',
            'can_export'            => true,
            //'show_in_rest'          => true,
            'supports'              => array('title'),
            'taxonomies'            => array('post_tag')
        );

        register_post_type('banner', $args);
    }

    function add_meta_boxes()
    {
        add_meta_box(
            'banner_link',
            'Banner Link',
            function ($post) {
                $banner_link = get_post_meta($post->ID, 'banner_link', true);
                $banner_rel = get_post_meta($post->ID, 'banner_rel', true);
                $banner_target_blank = get_post_meta($post->ID, 'banner_target_blank', true);
?>
            <label for="banner_link">Link:</label><br>
            <input type="text" id="banner_link" name="banner_link" value="<?php echo esc_attr($banner_link); ?>"><br><br>
            <div style="display:flex;justify-content: space-around;">
                <label>
                    <input type="radio" name="banner_rel" value="" <?php checked($banner_rel, ''); ?>>
                    No rel
                </label>
                <label>
                    <input type="radio" name="banner_rel" value="nofollow" <?php checked($banner_rel, 'nofollow'); ?>>
                    rel=nofollow
                </label>
                <label>
                    <input type="radio" name="banner_rel" value="sponsored" <?php checked($banner_rel, 'sponsored'); ?>>
                    rel=sponsored
                </label>
            </div><br><br>
            <label>
                <input type="checkbox" name="banner_target_blank" value="1" <?php checked($banner_target_blank, 1); ?>>
                Nuova tab
            </label><br><br>
<?php
            },
            'banner',
            'normal'
        );

        /*
        add_meta_box(
            'immagine_banner_metabox',
            'Immagini Banner',
            function ($post) {
                // Recupera i valori attuali dei campi
                $immagine_1 = get_post_meta($post->ID, 'immagine_1', true);
                $immagine_2 = get_post_meta($post->ID, 'immagine_2', true);
                
                // Campo per la prima immagine
                echo '<label for="immagine_1">Immagine 1:</label><br />';
                echo '<input type="text" id="immagine_1" name="immagine_1" value="' . esc_attr($immagine_1) . '" /><br />';
                echo '<button class="upload-image button">Carica Immagine</button><br />';
                
                // Campo per la seconda immagine
                echo '<label for="immagine_2">Immagine 2:</label><br />';
                echo '<input type="text" id="immagine_2" name="immagine_2" value="' . esc_attr($immagine_2) . '" /><br />';
                echo '<button class="upload-image button">Carica Immagine</button>';
            },
            'banner',
            'normal',
            'default'
        );
        */
    }

    function do_meta_boxes()
    {
        global $post_type, $wp_meta_boxes;
        if ($post_type == 'banner' && isset($wp_meta_boxes['banner'])) {
            // Sposta il box dei tag dalla colonna laterale alla colonna centrale
            if (isset($wp_meta_boxes['banner']['side']['tagsdiv-post_tag'])) {
                $wp_meta_boxes['banner']['normal']['core']['tagsdiv-post_tag'] = $wp_meta_boxes['banner']['side']['tagsdiv-post_tag'];
                unset($wp_meta_boxes['banner']['side']['tagsdiv-post_tag']);
            }

            // Sposta il box dell'immagine in evidenza dalla colonna laterale alla colonna centrale
            if (isset($wp_meta_boxes['banner']['side']['postimagediv'])) {
                $wp_meta_boxes['banner']['normal']['core']['postimagediv'] = $wp_meta_boxes['banner']['side']['postimagediv'];
                unset($wp_meta_boxes['banner']['side']['postimagediv']);
            }
        }
    }

    function add_rwmb_meta_boxes($meta_boxes)
    {
        $meta_boxes[] = array(
            'post_types' => [ 'banner' ],
            'title'      => __( 'Immagini banner', 'morph-banners' ),
            'id'         => 'informations',
            'context'    => 'normal',
            'fields'     => array(
                array(
                    'name'             => __('Immagine per desktop (default)', 'banner'),
                    'id'               => 'desktop_image',
                    'type'             => 'single_image',
                    'required'         => true,
                    'desc'             => 'Formato: 2:1',
                    'image_size'       => 'banner-desktop',
                ),
                array(
                    'name'             => __('Immagine per Mobile', 'banner'),
                    'id'               => 'mobile_image',
                    'type'             => 'single_image',
                    'desc'             => 'Formato: 3:2',
                    'image_size'       => 'banner-mobile',
                ),
            ),
        );

        return $meta_boxes;
    }

    function add_banner_images_size() {
        add_image_size( 'banner-desktop', 900, 450, true);
        add_image_size( 'banner-mobile', 900, 600, true );
    }

    function save_post_banner($post_id)
    {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

        if (isset($_POST['banner_link'])) {
            update_post_meta($post_id, 'banner_link', sanitize_text_field($_POST['banner_link']));
        }

        if (isset($_POST['banner_rel'])) {
            update_post_meta($post_id, 'banner_rel', sanitize_text_field($_POST['banner_rel']));
        }

        if (isset($_POST['banner_target_blank'])) {
            update_post_meta($post_id, 'banner_target_blank', 1);
        } else {
            update_post_meta($post_id, 'banner_target_blank', 0);
        }
    }
}
