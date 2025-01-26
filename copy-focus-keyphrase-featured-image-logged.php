function copy_focus_keyphrase_meta_description_to_social_meta_and_image() {
    global $wpdb;

    $log_file = WP_CONTENT_DIR . '/focus_keyphrase_log.txt';
    file_put_contents($log_file, "Starting update process...\n", FILE_APPEND);

    // Query to get all posts with Yoast SEO metadata
    $posts = $wpdb->get_results("
        SELECT post_id, meta_key, meta_value 
        FROM {$wpdb->postmeta} 
        WHERE meta_key IN ('_yoast_wpseo_focuskw', '_yoast_wpseo_metadesc', '_thumbnail_id')
    ");

    $post_meta = [];
    foreach ($posts as $post) {
        $post_meta[$post->post_id][$post->meta_key] = $post->meta_value;
    }

    foreach ($post_meta as $post_id => $meta) {
        $focus_keyphrase = isset($meta['_yoast_wpseo_focuskw']) ? $meta['_yoast_wpseo_focuskw'] : '';
        $meta_description = isset($meta['_yoast_wpseo_metadesc']) ? $meta['_yoast_wpseo_metadesc'] : '';
        $thumbnail_id = isset($meta['_thumbnail_id']) ? $meta['_thumbnail_id'] : '';

        // Update Social Title (og:title)
        if (!empty($focus_keyphrase)) {
            update_post_meta($post_id, '_yoast_wpseo_opengraph-title', $focus_keyphrase);
        }

        // Update Social Description (og:description)
        if (!empty($meta_description)) {
            update_post_meta($post_id, '_yoast_wpseo_opengraph-description', $meta_description);
        }

        // Update Social Image (og:image)
        if (!empty($thumbnail_id)) {
            $thumbnail_url = wp_get_attachment_url($thumbnail_id);
            if ($thumbnail_url) {
                update_post_meta($post_id, '_yoast_wpseo_opengraph-image', $thumbnail_url);
            }
        }

        file_put_contents($log_file, "Updated post ID {$post_id}\n", FILE_APPEND);
    }

    file_put_contents($log_file, "Process complete.\n", FILE_APPEND);
}
add_action('init', 'copy_focus_keyphrase_meta_description_to_social_meta_and_image');
