<?php
/**
 * Plugin Name: ACF PHP
 * Plugin URI: http://www.danielpost.com
 * Description: Supercharge your ACF development.
 * Author: Daniel Post
 * Version: 1.0
 * Author URI: http://www.danielpost.com
 */
namespace DP;

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use DP\ACFPHP\WordPress\Plugin;

spl_autoload_register(__NAMESPACE__ . '\\autoload');

new Plugin();

function autoload( $class ) {
    if( ! strstr( $class, 'DP\ACFPHP' ) ) {
        return;
    }

    $result = str_replace( 'DP\ACFPHP\\', '', $class );
    $result = str_replace( '\\', '/', $result );

    require $result . '.php';
}