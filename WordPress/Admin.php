<?php
namespace DP\ACFPHP\WordPress;

class Admin {

    const TEXT_DOMAIN = 'dp-acfphp-admin';

    public function __construct() {
        add_action( 'acf/init', array( $this, 'register_options_page' ) );
        add_action( 'acf/init', array( $this, 'register_options' ) );
        add_action( 'acf/init', array( $this, 'hide_acf_menu' ) );
        add_action( 'acf/init', array( $this, 'redirect_acf_menu' ) );
    }

    public function register_options_page() {
        if ( function_exists( 'acf_add_options_sub_page' ) ) {
            acf_add_options_sub_page( array(
                'title'      => __('ACF PHP', Admin::TEXT_DOMAIN),
                'parent'     => 'options-general.php',
                'capability' => 'manage_options'
            ) );
        }
    }

    public function register_options() {
        $acfphp_options = array(
            'key' => 'acfphp_options',
            'title' => __('Options', Admin::TEXT_DOMAIN),
            'location' => array(
                array(
                    array(
                        'param' => 'options_page',
                        'operator' => '==',
                        'value' => 'acf-options-acf-php',
                    ),
                ),
            ),
            'fields' => array(
                'dp_hide_acf_menu_item' => array(
                    'type' => 'true_false',
                    'label' => __('Hide ACF menu item', Admin::TEXT_DOMAIN),
                    'message' => __('Check to remove the "Custom Fields" menu item. It will also prevent users from accessing the ACF pages directly by redirecting them to the Admin Dashboard.', Admin::TEXT_DOMAIN),
                ),
                'dp_disable_acf_on_front_end' => array(
                    'type' => 'true_false',
                    'label' => __('Disable ACF on front end', Admin::TEXT_DOMAIN),
                    'message' => __('Check this to improve performance if you\'re using native WordPress functions to load the meta values. <em>WARNING: this will disable ACF functions like get_field().</em>', Admin::TEXT_DOMAIN),
                ),
            ),
        );

        new Metabox( $acfphp_options );
    }

    public function hide_acf_menu() {
        $hide_acf_menu = (int) get_option( 'options_dp_hide_acf_menu_item' );
        
        if ( 1 === $hide_acf_menu ) {
            add_filter('acf/settings/show_admin', '__return_false');
        }
    }

    public function redirect_acf_menu() {
        $hide_acf_menu = (int) get_option( 'options_dp_hide_acf_menu_item' );
        
        if ( isset( $_GET['post_type' ] ) && ( $_GET['post_type'] == 'acf-field-group' ) ) {
            if ( 1 === $hide_acf_menu ) {
                wp_redirect( admin_url() );
                die();
            }
        }
    }

}