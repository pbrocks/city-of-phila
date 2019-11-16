<?php

new Help_Welcome_Menus();
class Help_Welcome_Menus {
	public function __construct() {
		add_action( 'admin_init', array( $this, 'code_sample_welcome' ), 11 );
		add_action( 'admin_menu', array( $this, 'phila_code_sample_dashboard' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'initialize_code_sample_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'initialize_code_sample_scripts' ) );
		add_action( 'wp_ajax_code_sample_request', array( $this, 'run_code_sample_function' ) );
		add_action( 'wp_ajax_nopriv_code_sample_request', array( $this, 'run_code_sample_function' ) );
	}


	public function get_replacement_output() {
		global $post;
		$parent            = isset( $post->post_parent ) ? $post->post_parent : '';
		$parent_page_title = $parent
			? get_the_title( $parent )
			: '';
		// Do search w/ Ajax and populate these fields dynamically when a person starts typing
		return '
		<input type="hidden" name="parent_id" id="js-page-search" value="' . $parent . '"/>
		<input type="text" name="parent_page_title" id="js-page-search" value="' . $parent_page_title . '"/>
		';
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

		add_action( 'load-' . $code_sample_help_page, array( $this, 'admin_add_help_tab' ) );
	}

	/**
	 * Display the plugin code_sample message
	 */
	public function phila_code_sample_response() {
		global $code_sample_help_page;
		echo '<div class="wrap">';
		echo '<h2>' . ucwords( preg_replace( '/_+/', ' ', __FUNCTION__ ) ) . '</h2>';
		$response_tabs = new Phila_Code_Sample_Info();
		$hide_bouncing = get_user_meta( get_current_user_id(), 'hide_bouncing_arrow', true );
		if ( 'hide' !== $hide_bouncing ) {
			echo $hide_bouncing;
			$show_tabs = $response_tabs->phila_code_sample_arrow();
		}
		$get_settings = $response_tabs->phila_settings_page();
		$this->add_to_code_sample_dashboard();
		// $this->phila_code_sample_footer();
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
		// echo '<h2>' . basename( __FILE__ ) . __LINE__ . '</h2>';
	}

	/**
	 * [initialize_code_sample_scripts]
	 *
	 * @return [type] [description]
	 */
	public function initialize_code_sample_scripts() {
		wp_register_script( 'phila-code-sample', plugins_url( 'js/phila-code-sample.js', __DIR__ ), array( 'jquery' ), time(), true );
		wp_localize_script(
			'phila-code-sample',
			'code_sample_object',
			array(
				'code_ajaxurl'      => admin_url( 'admin-ajax.php' ),
				'random_number'     => time(),
				'code_sample_nonce' => wp_create_nonce( 'phila-code-sample-nonce' ),
				'explanation_one'   => 'Set up anything from the PHP side here in this function (' . __FUNCTION__ . '). Add the variable to the JS file.',
			)
		);
		wp_enqueue_script( 'phila-code-sample' );
	}

	public function run_code_sample_function() {
		$return_data                      = $_POST;
		$post_7                           = get_post( 7 );
		$title                            = $post_7->post_title;
		$return_data['explanation_three'] = 'You can also add data here in this function (' . __FUNCTION__ . ') if you need javascript to help you calculate first.';
		echo '<pre>$return_data ';
		print_r( $return_data );
		echo '</pre>';
		exit();
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

	public function admin_add_help_tab() {
		global $code_sample_help_page;
		$screen = get_current_screen();

		// Add my_help_tab if current screen is My Admin Page
		$screen->add_help_tab(
			array(
				'id'      => 'code_sample_help_tab_1',
				'title'   => __( 'Code Sample Help Tab One', 'phila-code-sample' ),
				'content' => '<h3>' . __( 'Code Sample Help Tab', 'phila-code-sample' ) . '</h3>' .
				'<p>' . __( 'Use this field to describe to the user what text you want on the help tab.', 'phila-code-sample' ) . '</p>',
			)
		);
		$screen->add_help_tab(
			array(
				'id'      => 'code_sample_help_tab_2',
				'title'   => __( 'Code Sample Help Tab Two', 'phila-code-sample' ),
				'content' => '<h3>' . __( 'Code Sample Help Tab', 'phila-code-sample' ) . '</h3>' .
				'<p>' . __( 'Use this field to describe to the user what text you want on the help tab.', 'phila-code-sample' ) . '</p>',
			)
		);
		$screen->add_help_tab(
			array(
				'id'      => 'code_sample_help_tab_3',
				'title'   => __( 'Request One', 'phila-code-sample' ),
				'content' => '<h3>' . __( 'Scenario 1', 'phila-code-sample' ) . '</h3>' .
				'<p>' . __( 'Marketing team members are having a hard time figuring out what page templates are being used on what pages. Write a plugin that lets authors easily see which template a page is using and also see only pages using a particular template.', 'phila-code-sample' ) . '</p>',
			)
		);
		$screen->add_help_tab(
			array(
				'id'      => 'code_sample_help_tab_4',
				'title'   => __( 'Request Two', 'phila-code-sample' ),
				'content' => '<h3>' . __( 'Code Sample Help Tab', 'phila-code-sample' ) . '</h3>' .
				'<p>' . __( 'The stakeholders want to be able to get featured posts out of many of our blogs and online magazines so they can put the posts into a widget on the philangine.com blog. Write a plugin for those WordPress sites that gives the post author a way to mark a post as "Featured on WP Engine\'s blog" and a way to get the 5 most recent featured posts out of the REST API.', 'phila-code-sample' ) . '</p>',
			)
		);
	}

}
