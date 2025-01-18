# Yoast Focus Keyphrase to Social Meta

**A WordPress script to automatically copy Yoast SEO's Focus Keyphrase to Social Title and Meta Description to Social Description.**

This repository contains two scripts:
1. A basic version without logging.
2. An advanced version with logging for debugging and monitoring.

This solution is ideal for WordPress websites with 900+ published articles where the Focus Keyphrase and Meta Description need to be copied into the Social Title and Social Description fields for better social media optimization.

---

## Features

- Automatically processes all published WordPress posts.
- Copies:
  - **Focus Keyphrase** ➡️ **Social Title** (og:title).
  - **Meta Description** ➡️ **Social Description** (og:description).
- Two versions:
  - **Without logging**: Lightweight and direct.
  - **With logging**: Tracks progress in a log file for debugging purposes.
- Easy to integrate via the Code Snippets plugin or custom PHP files.

---

## Requirements

- WordPress with Yoast SEO installed and configured.
- Access to the WordPress admin area or server files.
- PHP 7.4+.

---

## Files in This Repository

### 1. `copy-focus-keyphrase-basic.php`

A simple PHP script to copy the Focus Keyphrase and Meta Description to their respective social meta fields without generating any logs.

### 2. `copy-focus-keyphrase-logged.php`

An advanced version that creates a log file to track the script’s progress. The log file is saved in the `wp-content` directory as `focus_keyphrase_log.txt`.

---

## Installation and Usage

### **Step 1: Backup Your Website**

Before making any changes, back up your WordPress database and files.

### **Step 2: Choose Your Script Version**

- Use `copy-focus-keyphrase-basic.php` for direct execution.
- Use `copy-focus-keyphrase-logged.php` if you want to track script execution progress in a log file.

### **Step 3: Install via Code Snippets Plugin**

1. Install and activate the [Code Snippets](https://wordpress.org/plugins/code-snippets/) plugin.
2. Add a new snippet in the plugin.
3. Copy the contents of the selected script file into the snippet.
4. Save and activate the snippet.

### **Step 4: Verify Results**

- Visit a few articles and check the Social Title and Social Description fields.
- If using the logged version, view the log file at `wp-content/focus_keyphrase_log.txt` for progress details.

### **Step 5: Deactivate the Script**

After confirming the script has run successfully, deactivate and remove the snippet to prevent re-execution.

---

## Example Output (Logged Version)

Log file example:

```plaintext
Starting update process...
Updated post ID 123
Updated post ID 124
Updated post ID 125
Process complete.
```

---

## SEO Benefits

- **Improved Social Media Previews**: Ensures consistency between focus keyphrases, meta descriptions, and social media previews.
- **Streamlined Workflow**: Automates repetitive tasks, saving time for content creators and SEO specialists.

---

## Contribution

Feel free to submit issues or pull requests to improve the scripts.

---

## License

This project is licensed under the MIT License. See the `LICENSE` file for details.

---

## Contact

For queries or suggestions, contact the repository owner or create a GitHub issue.

---

### Example Code Snippet

#### Basic Version
```php
function copy_focus_keyphrase_meta_description_to_social_meta() {
    global $wpdb;

    $posts = $wpdb->get_results("SELECT post_id, meta_key, meta_value FROM {$wpdb->postmeta} WHERE meta_key IN ('_yoast_wpseo_focuskw', '_yoast_wpseo_metadesc')");

    $post_meta = [];
    foreach ($posts as $post) {
        $post_meta[$post->post_id][$post->meta_key] = $post->meta_value;
    }

    foreach ($post_meta as $post_id => $meta) {
        $focus_keyphrase = isset($meta['_yoast_wpseo_focuskw']) ? $meta['_yoast_wpseo_focuskw'] : '';
        $meta_description = isset($meta['_yoast_wpseo_metadesc']) ? $meta['_yoast_wpseo_metadesc'] : '';

        if (!empty($focus_keyphrase)) {
            update_post_meta($post_id, '_yoast_wpseo_opengraph-title', $focus_keyphrase);
        }

        if (!empty($meta_description)) {
            update_post_meta($post_id, '_yoast_wpseo_opengraph-description', $meta_description);
        }
    }
}
add_action('init', 'copy_focus_keyphrase_meta_description_to_social_meta');
```

#### Logged Version
Refer to the `copy-focus-keyphrase-logged.php` file in this repository.
