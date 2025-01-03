<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;

// Register the Elementor Widget
class News_Aggregator_Widget extends Widget_Base {

    public function get_name() {
        return 'news_aggregator_widget';
    }

    public function get_title() {
        return __('News Aggregator', 'news-aggregator');
    }

    public function get_icon() {
        return 'eicon-post-list';
    }

    public function get_categories() {
        return ['basic'];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'news-aggregator'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'feed_url',
            [
                'label' => __('RSS Feed URL', 'news-aggregator'),
                'type' => Controls_Manager::TEXT,
                'input_type' => 'url',
                'default' => '',
                'placeholder' => __('Enter RSS feed URL here', 'news-aggregator'),
            ]
        );

        $this->add_control(
            'items',
            [
                'label' => __('Number of Items', 'news-aggregator'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 100,
                'step' => 1,
                'default' => 5,
            ]
        );

        $this->add_control(
            'category',
            [
                'label' => __('Category', 'news-aggregator'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => __('Enter category name to filter', 'news-aggregator'),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_section',
            [
                'label' => __('Style', 'news-aggregator'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => __('Title Typography', 'news-aggregator'),
                'selector' => '{{WRAPPER}} .news-aggregator li a',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __('Title Color', 'news-aggregator'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .news-aggregator li a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'background',
                'label' => __('Background', 'news-aggregator'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .news-aggregator',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'label' => __('Border', 'news-aggregator'),
                'selector' => '{{WRAPPER}} .news-aggregator',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        if (empty($settings['feed_url'])) {
            echo '<p>' . __('No RSS feed URL provided.', 'news-aggregator') . '</p>';
            return;
        }

        echo do_shortcode('[news_aggregator feed_url="' . esc_attr($settings['feed_url']) . '" items="' . esc_attr($settings['items']) . '" category="' . esc_attr($settings['category']) . '"]');
    }
}

function register_news_aggregator_widget($widgets_manager) {
    $widgets_manager->register(new \News_Aggregator_Widget());
}
add_action('elementor/widgets/register', 'register_news_aggregator_widget');