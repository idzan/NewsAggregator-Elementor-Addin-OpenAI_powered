<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Shortcode to display aggregated news
function na_display_news($atts) {
    $atts = shortcode_atts([
        'feed_url' => '', // URL of the RSS feed
        'items' => 5,     // Number of items to display
        'category' => ''  // Category filter
    ], $atts);

    if (empty($atts['feed_url'])) {
        return '<p>No RSS feed URL provided.</p>';
    }

    $rss = fetch_feed($atts['feed_url']);

    if (is_wp_error($rss)) {
        return '<p>Unable to fetch the RSS feed.</p>';
    }

    $max_items = $rss->get_item_quantity($atts['items']);
    $rss_items = $rss->get_items(0, $max_items);

    if (empty($rss_items)) {
        return '<p>No items found in the RSS feed.</p>';
    }

    $output = '<ul class="news-aggregator">';

    foreach ($rss_items as $item) {
        $categories = $item->get_categories();
        $match_category = empty($atts['category']) || array_reduce($categories, function($carry, $category) use ($atts) {
            return $carry || strcasecmp($category->get_label(), $atts['category']) === 0;
        }, false);

        if (!$match_category) {
            continue;
        }

        $title = esc_html($item->get_title());
        $link = esc_url($item->get_permalink());
        $date = $item->get_date('F j, Y');

        // Get the content of the item and rewrite it
        $content = $item->get_content();
        $rewritten_content = na_rewrite_content($content);

        $output .= "<li><a href='$link' target='_blank'>$title</a> <small>($date)</small><br>$rewritten_content<br><strong>Source:</strong> <a href='$link' target='_blank'>$link</a></li>";
    }

    $output .= '</ul>';

    return $output;
}

add_shortcode('news_aggregator', 'na_display_news');