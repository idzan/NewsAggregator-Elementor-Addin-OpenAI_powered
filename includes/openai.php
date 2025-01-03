<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Function to rewrite content using OpenAI
function na_rewrite_content($content) {
    // Remove personal opinions and experiences
    $patterns = [
        '/\b(I think|In my opinion|Personally|To me)\b.*?(\.|\n)/i', // Matches personal opinions
        '/\b(My experience|I found|From my perspective)\b.*?(\.|\n)/i'  // Matches personal experiences
    ];
    $rewritten_content = preg_replace($patterns, '', $content);

    // Optional: Use OpenAI for further rewriting
    $api_key = get_option('na_openai_api_key'); // Fetch API key from settings
    if ($api_key) {
        $rewritten_content = na_openai_rewrite($rewritten_content, $api_key);
    }

    return trim($rewritten_content);
}

// Function to send content to OpenAI API
function na_openai_rewrite($content, $api_key) {
    $url = 'https://api.openai.com/v1/completions';
    $data = [
        'model' => 'text-davinci-003',
        'prompt' => "Proofread and rewrite the following content to make it polished and professional:\n\n$content",
        'max_tokens' => 500,
    ];

    $args = [
        'body' => json_encode($data),
        'headers' => [
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer $api_key",
        ],
    ];

    $response = wp_remote_post($url, $args);
    if (is_wp_error($response)) {
        return $content; // Return original content on error
    }

    $body = json_decode(wp_remote_retrieve_body($response), true);
    return $body['choices'][0]['text'] ?? $content;
}
