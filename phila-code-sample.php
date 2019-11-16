<?php
/**
 * Plugin Name: City of Phila Code Sample
 * Plugin URL: https://github.com/pbrocks/city-of-phila
 * Description: Functionality plugin writting in response to WP Engine Code Sample.
 * Author: pbrocks (Paul Barthmaier)
 * Version: 0.1.1
 * Author URI: https://github.com/pbrocks
 * Text Domain: phila-code-sample
 */

/**
 * Here we are checking that WordPress is loaded and denying browsers any direct access to the code. Functionality of this plugin's code and only be realized by running the plugin through WordPress.
 */
defined( 'ABSPATH' ) || die( 'File cannot be accessed directly' );

register_activation_hook( __FILE__, 'phila_code_sample_install' );
function phila_code_sample_install() {
	set_transient( 'code_sample_activated', true, 30 );
}

add_action( 'plugins_loaded', 'phila_code_sample_initialize_php' );
function phila_code_sample_initialize_php() {
	foreach ( glob( __DIR__ . '/inc/*.php' ) as $filename ) {
		require $filename;
	}

	foreach ( glob( __DIR__ . '/inc/classes/*.php' ) as $filename ) {
		require $filename;
	}
}
