function copy_focus_keyphrase_meta_description_to_social_meta() {
    global $wpdb;

    // Query to get all posts with Yoast SEO metadata
    $posts = $wpdb->get_results("
        SELECT post_id, meta_key, meta_value 
        FROM {$wpdb->postmeta} 
        WHERE meta_key IN ('_yoast_wpseo_focuskw', '_yoast_wpseo_metadesc')
    ");

    $post_meta = [];
    foreach ($posts as $post) {
        $post_meta[$post->post_id][$post->meta_key] = $post->meta_value;
    }

    foreach ($post_meta as $post_id => $meta) {
        $focus_keyphrase = isset($meta['_yoast_wpseo_focuskw']) ? $meta['_yoast_wpseo_focuskw'] : '';
        $meta_description = isset($meta['_yoast_wpseo_metadesc']) ? $meta['_yoast_wpseo_metadesc'] : '';

        // Update Social Title (og:title)
        if (!empty($focus_keyphrase)) {
            update_post_meta($post_id, '_yoast_wpseo_opengraph-title', $focus_keyphrase);
        }

        // Update Social Description (og:description)
        if (!empty($meta_description)) {
            update_post_meta($post_id, '_yoast_wpseo_opengraph-description', $meta_description);
        }
    }

    echo "Focus Keyphrase and Meta Description copied to Social Meta successfully.";
}
add_action('init', 'copy_focus_keyphrase_meta_description_to_social_meta');
