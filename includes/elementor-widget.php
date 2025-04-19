<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Check if Elementor is active
if (!did_action('elementor/loaded')) {
    return;
}

// Register Elementor widget
add_action('elementor/widgets/widgets_registered', 'ach_register_elementor_widget');

function ach_register_elementor_widget() {
    require_once ACH_PLUGIN_PATH . 'includes/class-achievements-elementor-widget.php';
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Achievements_Elementor_Widget());
}

// Register Elementor category
add_action('elementor/elements/categories_registered', 'ach_register_elementor_category');

function ach_register_elementor_category($elements_manager) {
    $elements_manager->add_category(
        'achievements',
        [
            'title' => __('Achievements', 'achievements-manager'),
            'icon' => 'fa fa-trophy',
        ]
    );
}
