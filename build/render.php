<?php

/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */
?>

<?php
if (is_singular('post')) {
    global $post;

    // Ottieni gli ID dei tag associati all'articolo
    $tag_ids = wp_get_post_tags($post->ID, array('fields' => 'ids'));

    // Se ci sono tag associati all'articolo
    if ($tag_ids) {
        // Query per ottenere i banner collegati ai tag dell'articolo
        $banner_query = new WP_Query(array(
            'post_type' => 'banner',
            'tag__in' => $tag_ids,
            'posts_per_page' => 1, // Mostra solo un banner
            'orderby' => 'rand', // Ordina i risultati in modo casuale
        ));

        // Verifica se sono stati trovati banner
        if ($banner_query->have_posts()) {
            // Ottieni il banner
            $banner_query->the_post();
            $banner_img_desktop = wp_get_attachment_image(get_post_meta($post->ID, 'desktop_image', true), 'banner-desktop');
            $banner_img_mobile = wp_get_attachment_image(get_post_meta($post->ID, 'mobile_image', true), 'banner-mobile');
            $banner_link = get_post_meta($post->ID, 'banner_link', true);
            $banner_target_blank = get_post_meta($post->ID, 'banner_target_blank', true);
            $banner_rel = get_post_meta($post->ID, 'banner_rel', true);

            // Costruisci il link con gli attributi SEO
            $link_attributes = ' href="' . esc_url($banner_link) . '"';
            if (!empty($banner_rel)) {
                $link_attributes .= ' rel="' . esc_attr($banner_rel) . '"';
            }
            if ($banner_target_blank) {
                $link_attributes .= ' target="_blank"';
            }

            $banner_output = '<a' . $link_attributes . '>';
            $banner_output .= $banner_img_desktop;
            $banner_output .= $banner_img_mobile;
            $banner_output .= '</a>';

            echo '<div class="banner-widget">';
?>
            <style>
                .attachment-banner-desktop {
                    display: block;
                }

                .attachment-banner-mobile {
                    display: none;
                }

                @media only screen and (max-width: 768px) {
                    .attachment-banner-desktop:not(:only-child) {
                        display: none;
                    }

                    .attachment-banner-mobile:not(:only-child) {
                        display: block;
                    }
                }
            </style>
<?php


            // Output del banner
            echo '<p style="text-align:center;font-size:0.5rem;color:#00000080;margin-bottom: 5px;">ADVERTISEMENT</p>' . $banner_output . '</div>';

            // Resetta le impostazioni di query
            wp_reset_postdata();
        }
    }
}
?>