<?php
class Scrolling_Headline_Widget extends \Elementor\Widget_Base {
    public function get_name() {
        return 'scrolling_headline';
    }

    public function get_title() {
        return __('Scrolling Headline', 'scrolling-headline-elementor');
    }

    public function get_icon() {
        return 'eicon-t-letter';
    }

    public function get_categories() {
        return ['basic'];
    }

    public function get_script_depends() {
        return [];
    }

    public function get_style_depends() {
        return ['scrolling-headline'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_announcements',
            [
                'label' => __('Announcements', 'scrolling-headline-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'announcement_text',
            [
                'label' => __('Text', 'scrolling-headline-elementor'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Your Announcement Here', 'scrolling-headline-elementor'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'announcements',
            [
                'label' => __('Announcement Items', 'scrolling-headline-elementor'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'announcement_text' => __('Your First Announcement', 'scrolling-headline-elementor'),
                    ],
                    [
                        'announcement_text' => __('Your Second Announcement', 'scrolling-headline-elementor'),
                    ],
                ],
                'title_field' => '{{{ announcement_text }}}',
            ]
        );

        $this->add_control(
            'scroll_speed',
            [
                'label' => __('Scroll Speed (seconds)', 'scrolling-headline-elementor'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 100,
                'step' => 1,
                'default' => 20,
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'scroll_direction',
            [
                'label' => __('Scroll Direction', 'scrolling-headline-elementor'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'scroll-left',
                'options' => [
                    'scroll-left' => __('Left', 'scrolling-headline-elementor'),
                    'scroll-right' => __('Right', 'scrolling-headline-elementor'),
                ],
                'frontend_available' => true,
            ]
        );

        $this->end_controls_section();

        // Style Section
        $this->start_controls_section(
            'section_style',
            [
                'label' => __('Style', 'scrolling-headline-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'background_color',
            [
                'label' => __('Background Color', 'scrolling-headline-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#eff5ff',
                'selectors' => [
                    '{{WRAPPER}} .scrolling-text-container' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Text Color', 'scrolling-headline-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .scrolling-text-item' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'selector' => '{{WRAPPER}} .scrolling-text-item',
            ]
        );

        $this->add_responsive_control(
            'border_radius',
            [
                'label' => __('Border Radius', 'scrolling-headline-elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 4,
                    'right' => 4,
                    'bottom' => 4,
                    'left' => 4,
                    'unit' => 'px',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .scrolling-text-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $this->add_render_attribute('container', [
            'class' => 'scrolling-text-container',
        ]);

        $this->add_render_attribute('inner', [
            'class' => 'scrolling-text-inner',
            'role' => 'marquee',
            'style' => sprintf(
                '--marquee-speed: %ds; --direction: %s',
                $settings['scroll_speed'],
                $settings['scroll_direction']
            ),
        ]);
        ?>
        <div <?php echo $this->get_render_attribute_string('container'); ?>>
            <div <?php echo $this->get_render_attribute_string('inner'); ?>>
                <div class="scrolling-text">
                    <?php foreach ($settings['announcements'] as $item) : ?>
                        <div class="scrolling-text-item"><?php echo esc_html($item['announcement_text']); ?></div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php
    }
} 