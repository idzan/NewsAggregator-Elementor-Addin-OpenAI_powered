<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Add a settings page for the plugin
function na_add_settings_page() {
    add_options_page(
        'News Aggregator Settings',
        'News Aggregator',
        'manage_options',
        'news-aggregator',
        'na_render_settings_page'
    );
}
add_action('admin_menu', 'na_add_settings_page');

// Render the settings page
function na_render_settings_page() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['na_openai_api_key'])) {
        update_option('na_openai_api_key', sanitize_text_field($_POST['na_openai_api_key']));
        echo '<div class="updated"><p>API Key saved successfully.</p></div>';
    }

    $api_key = get_option('na_openai_api_key', '');

    echo '<div class="wrap">';
    echo '<h1>News Aggregator Settings</h1>';
    echo '<form method="post">';
    echo '<label for="na_openai_api_key">OpenAI API Key:</label>'; 
    echo '<input type="text" id="na_openai_api_key" name="na_openai_api_key" value="' . esc_attr($api_key) . '" style="width: 100%;" placeholder="Enter your OpenAI API Key">';
    echo '<p class="description">Enter your OpenAI API key to enable content rewriting and proofreading.</p>';
    echo '<input type="submit" value="Save API Key" class="button button-primary">';
    echo '</form>';
    echo '</div>';
}