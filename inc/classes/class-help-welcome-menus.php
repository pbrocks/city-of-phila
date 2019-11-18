<?php

new Help_Welcome_Menus();
class Help_Welcome_Menus {
	public function __construct() {
		add_action( 'admin_init', array( $this, 'code_sample_welcome' ), 11 );
		add_action( 'admin_menu', array( $this, 'phila_code_sample_dashboard' ) );
	}


	/**
	 * Add a page to the dashboard menu.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function phila_code_sample_dashboard() {
		global $code_sample_help_page;
		$slug                  = preg_replace( '/_+/', '-', __FUNCTION__ );
		$label                 = ucwords( preg_replace( '/_+/', ' ', __FUNCTION__ ) );
		$code_sample_help_page = add_dashboard_page( __( $label, 'phila-code-sample' ), __( $label, 'phila-code-sample' ), 'manage_options', $slug . '.php', array( $this, 'phila_code_sample_response' ) );
	}

	/**
	 * Display the plugin code_sample message
	 */
	public function phila_code_sample_response() {
		global $code_sample_help_page;
		echo '<div class="wrap">';
		echo '<h2>' . ucwords( preg_replace( '/_+/', ' ', __FUNCTION__ ) ) . '</h2>';
		$response_tabs = new Phila_Code_Sample_Info();

		$this->add_to_code_sample_dashboard();
		$this->phila_code_sample_footer();
		echo '</div>';
	}

	/**
	 * [phila_code_sample_footer]
	 *
	 * @return [type] [description]
	 */
	public function phila_code_sample_footer() {
		echo '<div id="' . preg_replace( '/_+/', '-', __FUNCTION__ ) . '">';
		echo '<h2>$code_sample_help_page <span style="color:salmon;"> = ' . $code_sample_help_page . '</span></h2>';
		echo '<h3>' . __FILE__ . '</h3>';
		echo '</div>';
	}


	/**
	 * Add a page to the dashboard menu.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function add_to_code_sample_dashboard() {
		echo '<h2>' . basename( __FILE__ ) . __LINE__ . '</h2>';
	}

	/**
	 * Check the plugin activated transient exists if does then redirect
	 */
	public function code_sample_welcome() {
		if ( ! get_transient( 'code_sample_activated' ) ) {
			return;
		}

		// Delete the plugin activated transient
		delete_transient( 'code_sample_activated' );

		wp_safe_redirect(
			add_query_arg(
				array(
					'page' => 'phila-code-sample-dashboard.php',
				),
				admin_url( 'index.php' )
			)
		);
		exit;
	}

}
