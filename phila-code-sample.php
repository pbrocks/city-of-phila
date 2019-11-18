<?php
/**
 * Plugin Name: City of Phila Code Sample
 * Plugin URL: https://github.com/pbrocks/city-of-phila
 * Description: Functionality plugin written for City of Philadelphia according to specs found in https://docs.google.com/presentation/d/1mxJrJizW8aVye65NAzqNJs1KSIie0mE06H9VmCG1k_I/edit#slide=id.g5b4da5837f_0_6.
 * Author: pbrocks (Paul Barthmaier)
 * Version: 0.1.2
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

/**
 * Setup WordPress localization support
 *
 * @since 1.0
 */
function phila_code_sample_load_textdomain() {
	load_plugin_textdomain( 'phila-code-sample', false, basename( dirname( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'phila_code_sample_load_textdomain' );

/**
 * [phila_code_sample_plugin_action_links description]
 *
 * @param  [type] $links [description]
 * @return [type]        [description]
 */
function phila_code_sample_plugin_action_links( $links ) {
	$action_links[] =
	'<a href="' . admin_url( 'customize.php?autofocus[panel]=sidetrack_login_panel' ) . '">' . __( 'Settings', 'phila-code-sample' ) . '</a>';
	return array_merge( $links, $action_links );
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'phila_code_sample_plugin_action_links' );
