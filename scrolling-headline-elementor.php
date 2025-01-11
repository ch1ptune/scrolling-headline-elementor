<?php
/**
 * Plugin Name: Scrolling Headline for Elementor
 * Description: Adds a scrolling headline widget to Elementor
 * Version: 1.0.1
 * Author: Your Name
 * Text Domain: scrolling-headline-elementor
 * GitHub Plugin URI: https://github.com/ch1ptune/scrolling-headline-elementor
 * GitHub Branch: main
 * License: GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

if (!defined('ABSPATH')) {
    exit;
}

final class Scrolling_Headline_Elementor {
    const VERSION = '1.0.1';
    const MINIMUM_ELEMENTOR_VERSION = '3.0.0';
    const MINIMUM_PHP_VERSION = '7.0';

    private static $_instance = null;

    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        add_action('plugins_loaded', [$this, 'init']);
    }

    public function init() {
        // Check if Elementor installed and activated
        if (!did_action('elementor/loaded')) {
            add_action('admin_notices', [$this, 'admin_notice_missing_elementor']);
            return;
        }

        // Add Plugin actions
        add_action('elementor/widgets/widgets_registered', [$this, 'init_widgets']);
        add_action('elementor/frontend/after_enqueue_styles', [$this, 'widget_styles']);
    }

    public function widget_styles() {
        wp_register_style('scrolling-headline', plugins_url('assets/css/scrolling-headline.css', __FILE__));
        wp_enqueue_style('scrolling-headline');
    }

    public function init_widgets() {
        require_once(__DIR__ . '/widgets/scrolling-headline.php');
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Scrolling_Headline_Widget());
    }

    public function admin_notice_missing_elementor() {
        if (isset($_GET['activate'])) unset($_GET['activate']);

        $message = sprintf(
            esc_html__('"%1$s" requires "%2$s" to be installed and activated.', 'scrolling-headline-elementor'),
            '<strong>' . esc_html__('Scrolling Headline for Elementor', 'scrolling-headline-elementor') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'scrolling-headline-elementor') . '</strong>'
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }
}

Scrolling_Headline_Elementor::instance(); 