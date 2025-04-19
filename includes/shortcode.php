<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Register shortcode
add_shortcode('display_achievements', 'ach_display_achievements_shortcode');

function ach_display_achievements_shortcode($atts) {
    // Parse attributes
    $atts = shortcode_atts(
        array(
            'category' => '',
            'limit' => -1,
        ),
        $atts,
        'display_achievements'
    );
    
    // Get achievements
    $achievements = get_option('ach_achievements', array());
    
    // Filter by category if specified
    if (!empty($atts['category'])) {
        $filtered_achievements = array();
        foreach ($achievements as $achievement) {
            if (strtolower($achievement['category']) === strtolower($atts['category'])) {
                $filtered_achievements[] = $achievement;
            }
        }
        $achievements = $filtered_achievements;
    }
    
    // Apply limit
    if ($atts['limit'] > 0 && count($achievements) > $atts['limit']) {
        $achievements = array_slice($achievements, 0, $atts['limit']);
    }
    
    // Generate a unique ID for this slider instance
    $slider_id = 'ach-slider-' . uniqid();
    
    // Start output buffering
    ob_start();
    
    if (empty($achievements)) {
        echo '<div class="ach-empty-message">' . esc_html__('No achievements found.', 'achievements-manager') . '</div>';
    } else {
        ?>
        <div class="ach-achievements-container">
            <div class="swiper <?php echo esc_attr($slider_id); ?>">
                <div class="swiper-wrapper">
                    <?php foreach ($achievements as $achievement) : ?>
                        <div class="swiper-slide">
                            <div class="ach-achievement-item">
                                <div class="ach-achievement-icon">
                                    <i class="dashicons <?php echo esc_attr($achievement['icon']); ?>"></i>
                                </div>
                                <div class="ach-achievement-content">
                                    <h3 class="ach-achievement-name"><?php echo esc_html($achievement['name']); ?></h3>
                                    <div class="ach-achievement-category"><?php echo esc_html($achievement['category']); ?></div>
                                    <?php if (isset($achievement['date'])) : ?>
                                        <div class="ach-achievement-date"><?php echo esc_html(date('M d, Y', strtotime($achievement['date']))); ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-pagination"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        </div>
        
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                new Swiper('.<?php echo esc_js($slider_id); ?>', {
                    slidesPerView: 1,
                    spaceBetween: 20,
                    pagination: {
                        el: '.<?php echo esc_js($slider_id); ?> .swiper-pagination',
                        clickable: true,
                    },
                    navigation: {
                        nextEl: '.<?php echo esc_js($slider_id); ?> .swiper-button-next',
                        prevEl: '.<?php echo esc_js($slider_id); ?> .swiper-button-prev',
                    },
                    breakpoints: {
                        640: {
                            slidesPerView: 2,
                        },
                        768: {
                            slidesPerView: 3,
                        },
                        1024: {
                            slidesPerView: 4,
                        },
                    },
                });
            });
        </script>
        <?php
    }
    
    // Return the buffered content
    return ob_get_clean();
}
