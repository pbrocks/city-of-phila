<?php
/**
 * File dedicated to enqueueing the scripts and styles. This file runs because it's included in the base plugin file. This is a separate php file that just loads the enqueue actions.
 *
 * Keeping files distinct according to their function is a way of maintaining sanity for developers. If there is an issue with a given file, its functions can be turned of in an instant by commenting out the line of code in the base file that includes this file in the plugin.
 */


add_action( 'admin_enqueue_scripts', 'phila_enqueue_dashboard_scripts_styles' );
function phila_enqueue_dashboard_scripts_styles() {
	wp_register_style( 'plugin-dash', plugins_url( 'css/dashboard-customization.css', __FILE__ ) );
	wp_enqueue_style( 'plugin-dash' );
}

add_action( 'login_enqueue_scripts', 'phila_enqueue_login_scripts_styles' );
function phila_enqueue_login_scripts_styles() {
	wp_register_style( 'login-page', plugins_url( 'css/login-customizations.css', __FILE__ ) );
	wp_enqueue_style( 'login-page' );
}

add_action( 'wp_enqueue_scripts', 'phila_enqueue_frontend_scripts_styles' );
function phila_enqueue_frontend_scripts_styles() {
	wp_register_style( 'plugin-frontend', plugins_url( 'css/frontend-customization.css', __FILE__ ) );
	wp_enqueue_style( 'plugin-frontend' );
}
