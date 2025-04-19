<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class Achievements_Elementor_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'achievements_display';
    }

    public function get_title() {
        return __('Achievements Display', 'achievements-manager');
    }

    public function get_icon() {
        return 'eicon-trophy';
    }

    public function get_categories() {
        return ['achievements'];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'achievements-manager'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'category',
            [
                'label' => __('Filter by Category', 'achievements-manager'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => __('Leave empty to show all', 'achievements-manager'),
            ]
        );

        $this->add_control(
            'limit',
            [
                'label' => __('Number of Achievements', 'achievements-manager'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => -1,
                'max' => 100,
                'step' => 1,
                'default' => -1,
                'description' => __('Enter -1 to show all', 'achievements-manager'),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_section',
            [
                'label' => __('Style', 'achievements-manager'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'columns',
            [
                'label' => __('Columns', 'achievements-manager'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '3',
                'options' => [
                    '1' => __('1', 'achievements-manager'),
                    '2' => __('2', 'achievements-manager'),
                    '3' => __('3', 'achievements-manager'),
                    '4' => __('4', 'achievements-manager'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .ach-achievements-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
                ],
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => __('Icon Color', 'achievements-manager'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4054b2',
                'selectors' => [
                    '{{WRAPPER}} .ach-achievement-icon i' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __('Title Color', 'achievements-manager'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .ach-achievement-name' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'category_color',
            [
                'label' => __('Category Color', 'achievements-manager'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#777777',
                'selectors' => [
                    '{{WRAPPER}} .ach-achievement-category' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'background_color',
            [
                'label' => __('Item Background', 'achievements-manager'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ach-achievement-item' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $shortcode = '[display_achievements';
        
        if (!empty($settings['category'])) {
            $shortcode .= ' category="' . esc_attr($settings['category']) . '"';
        }
        
        if (isset($settings['limit'])) {
            $shortcode .= ' limit="' . intval($settings['limit']) . '"';
        }
        
        $shortcode .= ']';
        
        echo do_shortcode($shortcode);
    }

    protected function _content_template() {
        ?>
        <div class="elementor-achievements-placeholder">
            <div style="text-align: center; padding: 20px;">
                <i class="fa fa-trophy" style="font-size: 30px; margin-bottom: 10px;"></i>
                <p><?php echo __('Achievements will be displayed here', 'achievements-manager'); ?></p>
            </div>
        </div>
        <?php
    }
}
