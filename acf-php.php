<?php
/**
 * Plugin Name:       ACF PHP
 * Plugin URI:        http://danielpost.com/
 * Description:       Supercharge your ACF development.
 * Version:           1.0.0
 * Author:            Daniel Post
 * Author URI:        http://danielpost.com/
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       acf-php
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require plugin_dir_path( __FILE__ ) . 'includes/class-acf-php.php';

function run_acf_php() {

	$plugin = new ACF_PHP();

}
run_acf_php();
