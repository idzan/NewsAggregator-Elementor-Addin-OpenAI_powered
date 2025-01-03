<?php
/*
Plugin Name: News Aggregator
Plugin URI: https://techbrief.news
Description: A simple plugin to aggregate news from one site to another using RSS feeds, with options to rewrite content, style based on theme, and proofread using OpenAI.
Version: 1.6
Author: Marko Idzan
Author URI: https://idzan.hr
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Include necessary files
require_once plugin_dir_path(__FILE__) . 'includes/shortcode.php';
require_once plugin_dir_path(__FILE__) . 'includes/openai.php';
require_once plugin_dir_path(__FILE__) . 'includes/elementor-widget.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin-settings.php';

// Enqueue basic styles for the news list
function na_enqueue_styles() {
    wp_enqueue_style('news-aggregator-style', plugins_url('assets/style.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'na_enqueue_styles');
?>