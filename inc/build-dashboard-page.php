<?php
add_action( 'wp_before_admin_bar_render', 'phila_show_template', 999 );
add_filter( 'admin_footer_text', 'phila_change_admin_footer' );

/**
 * [phila_show_template]
 *
 * @return [type] [description]
 */
function phila_show_template() {
	global $wp_admin_bar, $template;

	if ( is_page() ) {
		$page_template = get_page_template();
	} else {
		$page_template = $template;
	}

	$args = array(
		'id'    => 'phila-page-template',
		'title' => __( $page_template, 'phila-code-sample' ),
		'meta'  => array(
			'class' => 'philatemplate',
		),
	);
	$wp_admin_bar->add_menu( $args );

}
/**
 * [phila_change_admin_footer]
 *
 * Adding some info to footer of Welcome Page
 *
 * @param  [type] $footer_text [description]
 * @return [type]              [description]
 */
function phila_change_admin_footer( $footer_text ) {
	global $code_sample_help_page;
	$screen_check = get_current_screen();
	if ( $code_sample_help_page == $screen_check->id ) {
		return '<span id="footer-note">Deliciously delivered by <a href="https://github.com/pbrocks/phila-code-sample" target="_blank">pbrocks</a>. | ♥ | Code located on your server at <span style="color:slategray;">' . __FILE__ . '</span></span><br>';
	} else {
		return $footer_text;
	}
}
