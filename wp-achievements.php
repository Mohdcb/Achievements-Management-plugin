<?php
/**
 * Plugin Name: Achievements Manager
 * Plugin URI: https://perfviz.com/plugins/achievements-manager
 * Description: Manage and display achievements with a clean UI and slider shortcode
 * Version: 1.0.0
 * Author: Perfviz
 * Author URI: https://perfviz.com
 * Text Domain: achievements-manager
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('ACH_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('ACH_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
require_once ACH_PLUGIN_PATH . 'admin/admin-page.php';
require_once ACH_PLUGIN_PATH . 'includes/shortcode.php';

// Register activation hook
register_activation_hook(__FILE__, 'ach_activate_plugin');

function ach_activate_plugin() {
    // Initialize default achievements if none exist
    if (!get_option('ach_achievements')) {
        $default_achievements = array(
            array(
                'id' => uniqid(),
                'name' => 'First Achievement',
                'category' => 'General',
                'icon' => 'dashicons-awards',
                'date' => date('Y-m-d'),
            )
        );
        update_option('ach_achievements', $default_achievements);
    }
    
    // Initialize categories
    if (!get_option('ach_categories')) {
        $default_categories = array('General');
        update_option('ach_categories', $default_categories);
    }
}

// Enqueue admin scripts and styles
function ach_enqueue_admin_scripts($hook) {
    if ('settings_page_achievements-manager' !== $hook) {
        return;
    }
    
    wp_enqueue_style('ach-admin-styles', ACH_PLUGIN_URL . 'admin/css/admin-styles.css', array(), '1.0.0');
    wp_enqueue_script('ach-admin-script', ACH_PLUGIN_URL . 'admin/js/admin-script.js', array('jquery'), '1.0.0', true);
    wp_enqueue_style('wp-jquery-ui-dialog');
    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_style('jquery-ui-datepicker-style', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
    
    wp_localize_script('ach-admin-script', 'achObj', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('ach_nonce')
    ));
}
add_action('admin_enqueue_scripts', 'ach_enqueue_admin_scripts');

// Enqueue frontend scripts and styles
function ach_enqueue_frontend_scripts() {
    wp_enqueue_style('dashicons');
    wp_enqueue_style('onest-font', 'https://fonts.googleapis.com/css2?family=Onest:wght@300;400;500;600;700&display=swap');
    wp_enqueue_style('ach-frontend-styles', ACH_PLUGIN_URL . 'public/css/frontend-styles.css', array(), '1.0.0');
    
    // Enqueue Swiper JS
    wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css');
    wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js', array(), '10.0.0', true);
    wp_enqueue_script('ach-frontend-script', ACH_PLUGIN_URL . 'public/js/frontend-script.js', array('jquery', 'swiper-js'), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'ach_enqueue_frontend_scripts');

// AJAX handler for saving achievements
function ach_save_achievements() {
    // Check nonce for security
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'ach_nonce')) {
        wp_send_json_error('Security check failed');
    }
    
    // Sanitize and save achievements data
    if (isset($_POST['achievements'])) {
        $achievements = json_decode(stripslashes($_POST['achievements']), true);
        $sanitized_achievements = array();
        
        foreach ($achievements as $achievement) {
            $sanitized_achievements[] = array(
                'id' => sanitize_text_field($achievement['id']),
                'name' => sanitize_text_field($achievement['name']),
                'category' => sanitize_text_field($achievement['category']),
                'icon' => sanitize_text_field($achievement['icon']),
                'date' => sanitize_text_field($achievement['date']),
            );
        }
        
        update_option('ach_achievements', $sanitized_achievements);
        wp_send_json_success('Achievements saved successfully');
    } else {
        wp_send_json_error('No data received');
    }
}
add_action('wp_ajax_ach_save_achievements', 'ach_save_achievements');

// AJAX handler for saving categories
function ach_save_categories() {
    // Check nonce for security
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'ach_nonce')) {
        wp_send_json_error('Security check failed');
    }
    
    // Sanitize and save categories data
    if (isset($_POST['categories'])) {
        $categories = json_decode(stripslashes($_POST['categories']), true);
        $sanitized_categories = array();
        
        foreach ($categories as $category) {
            $sanitized_categories[] = sanitize_text_field($category);
        }
        
        update_option('ach_categories', $sanitized_categories);
        wp_send_json_success('Categories saved successfully');
    } else {
        wp_send_json_error('No data received');
    }
}
add_action('wp_ajax_ach_save_categories', 'ach_save_categories');

// Create admin menu
function ach_add_admin_menu() {
    add_options_page(
        'Achievements Manager',
        'Achievements',
        'manage_options',
        'achievements-manager',
        'ach_admin_page'
    );
}
add_action('admin_menu', 'ach_add_admin_menu');
