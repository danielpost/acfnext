<?php
namespace DP\ACFPHP\WordPress;

class Plugin {
    
    const TEXT_DOMAIN = 'dp-acfphp';

    // Initialize after all plugins are loaded to ensure ACF is loaded
    public function __construct() {
        add_action( 'plugins_loaded', array( $this, 'initialize' ) );
    }

    public function initialize() {
        // Kill plugin if ACF isn't activated
        if( ! class_exists( '\acf' ) ) {
            return;
        }

        // Initialize admin functionality
        if( is_admin() ) {
            new Admin();
        }

        add_filter( 'option_active_plugins', array( $this, 'disable_acf_on_frontend' ) );
    }

    public function disable_acf_on_frontend( $plugins ) {
        $disable_acf_on_front_end = (int) get_option( 'options_dp_disable_acf_on_front_end' );
                   
        if ( is_admin() ) {
            return $plugins;
        }

        if ( 1 === $disable_acf_on_front_end ) {
            foreach( $plugins as $i => $plugin ) {
                if ( 'advanced-custom-fields-pro/acf.php' == $plugin ) {
                    unset( $plugins[$i] );
                }
            }
        }

        return $plugins;
    }

}