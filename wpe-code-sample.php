<?php
/**
 * Plugin Name: WPE Code Sample
 * Plugin URL: https://github.com/pbrocks/wpe-code-sample
 * Description: Functionality plugin writting in response to WP Engine Code Sample.
 * Author: pbrocks (Paul Barthmaier)
 * Version: 1.3
 * Author URI: https://github.com/pbrocks
 * Text Domain: wpe-code-sample
 */

/**
 * Here we are checking that WordPress is loaded and denying browsers any direct access to the code. Functionality of this plugin's code and only be realized by running the plugin through WordPress.
 */
defined( 'ABSPATH' ) || die( 'File cannot be accessed directly' );

register_activation_hook( __FILE__, 'wpe_code_sample_install' );
function wpe_code_sample_install() {
	set_transient( 'code_sample_activated', true, 30 );
}

/**
 * This line includes all php files located in the /inc folder. If there is a problem with the code in the file, you can comment this line out by placing two forward slashes '//' in front of the require statement which turns off all code in that directory.
 */
foreach ( glob( __DIR__ . '/inc/*.php' ) as $filename ) {
	require $filename;
}


foreach ( glob( __DIR__ . '/inc/classes/*.php' ) as $filename ) {
	require $filename;
}
