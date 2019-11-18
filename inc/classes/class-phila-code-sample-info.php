<?php

new Phila_Code_Sample_Info();
class Phila_Code_Sample_Info {
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'phila_code_sample_dashboard' ) );
		add_action( 'admin_init', array( $this, 'code_sample_welcome' ), 11 );
		add_action( 'admin_enqueue_scripts', array( $this, 'initialize_cphila_admin_styles' ) );
	}
	/**
	 * Add a page to the dashboard menu.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function phila_code_sample_dashboard() {
		global $code_sample_page;
		$slug             = preg_replace( '/_+/', '-', __FUNCTION__ );
		$label            = ucwords( preg_replace( '/_+/', ' ', __FUNCTION__ ) );
		$code_sample_page = add_dashboard_page( __( $label, 'phila-code-sample' ), __( $label, 'phila-code-sample' ), 'manage_options', $slug . '.php', array( $this, 'phila_code_sample_response' ) );
	}

	/**
	 * Display the plugin code_sample message
	 */
	public function phila_code_sample_response() {
		global $code_sample_page;
		echo '<div class="wrap">';
		echo '<h2>' . ucwords( preg_replace( '/_+/', ' ', __FUNCTION__ ) ) . '</h2>';

		$this->phila_settings_page();
		echo '</div>';
	}

	/**
	 * [initialize_cphila_admin_styles]
	 *
	 * @return [type] [description]
	 */
	public function initialize_cphila_admin_styles() {
		wp_register_style( 'phila-code-sample', plugins_url( 'css/phila-code-sample.css', __DIR__ ), [], time() );
		wp_enqueue_style( 'phila-code-sample' );

	}

	public function phila_admin_tabs( $current = 'overview' ) {
		$tabs  = array(
			'overview'           => 'Overview',
			'background'         => 'Empty Post Edit Screen',
			'empty_frontend'     => 'Empty Frontend',
			'backend_data'       => 'Data in Backend',
			'frontend_data_2019' => 'Data on 2019 Frontend',
			'frontend_data_2020' => 'Data on 2020 Frontend',
		);
		$links = array();
		echo '<div id="icon-themes" class="icon32"><br></div>';
		echo '<h2 class="nav-tab-wrapper">';
		foreach ( $tabs as $tab => $name ) {
			$class = ( $tab == $current ) ? ' nav-tab-active' : '';
			echo "<a class='nav-tab$class' href='?page=phila-code-sample-dashboard.php&tab=$tab'>$name</a>";

		}
		echo '</h2>';
	}

	public function get_phila_setup_data() {
		$plugin_data['Name'] = 'Phila Code Sample';
		return $plugin_data;
	}

	public function phila_settings_page() {
		global $pagenow, $template;
		// $settings = get_option( 'phila_tabbed_settings' );
		?>
	
	<div class="tabs-wrap">	
		<?php
		if ( isset( $_GET['tab'] ) ) {
			$this->phila_admin_tabs( $_GET['tab'] );
		} else {
			$this->phila_admin_tabs( 'overview' );
		}
		?>

	<div id="poststuff">
			<?php
			if ( $pagenow == 'index.php' && $_GET['page'] == 'phila-code-sample-dashboard.php' ) {

				if ( isset( $_GET['tab'] ) ) {
					$tab = $_GET['tab'];
				} else {
					$tab = 'overview';
				}

				switch ( $tab ) {
					case 'background':
						?>
							<grid-cell class="grid-head-label">
								<h2 class="description">Phila Code Challenge</h2>
							</grid-cell>						
							<grid-cell class="content">
								<p class="description">Thought process behind the code rendering these screens:</p>
								<p><img src="<?php echo plugins_url( 'images/hello-world-admin.png', dirname( __DIR__ ) ); ?>" width="95%" /></p>
							</grid-cell>
						<?php
						break;
					case 'empty_frontend':
						?>
							<grid-cell class="grid-head-label">
								<h2 class="description"><?php echo esc_html( ucwords( preg_replace( '/_+/', ' ', $tab ) ) ); ?></h2>
							</grid-cell>						
							<grid-cell class="content">
							<p class="description">
								<img src="<?php echo plugins_url( 'images/hello-world-frontend.png', dirname( __DIR__ ) ); ?>" width="95%" />
							</p>
							</grid-cell>						
							<?php
						break;
					case 'backend_data':
						?>
							<grid-cell class="grid-head-label">
								<h2 class="description"><?php echo esc_html( ucwords( preg_replace( '/_+/', ' ', $tab ) ) ); ?></h2>
							</grid-cell>						
							<grid-cell class="content">
							<p class="description">
								<img src="<?php echo plugins_url( 'images/backend-with-data.png', dirname( __DIR__ ) ); ?>" width="95%" />
							</p>
							</grid-cell>
								<?php
						break;
					case 'frontend_data_2019':
						?>
							<grid-cell class="grid-head-label">
								<h2 class="description"><?php echo esc_html( ucwords( preg_replace( '/_+/', ' ', $tab ) ) ); ?></h2>
							</grid-cell>						
							<grid-cell class="content">
								<h3>Twenty Nineteen Theme</h3>
							<p class="description">
								<img src="<?php echo plugins_url( 'images/frontend-with-data.png', dirname( __DIR__ ) ); ?>" width="95%" />
							</p>
							</grid-cell>
								<?php
						break;
					case 'frontend_data_2020':
						?>
							<grid-cell class="grid-head-label">
								<h2 class="description"><?php echo esc_html( ucwords( preg_replace( '/_+/', ' ', $tab ) ) ); ?></h2>
							</grid-cell>						
							<grid-cell class="content">
								<h3>Twenty Twenty Theme</h3>
							<p class="description">
								<img src="<?php echo plugins_url( 'images/frontend-with-data-2020.png', dirname( __DIR__ ) ); ?>" width="95%" />
							</p>
							</grid-cell>
								<?php
						break;
					case 'overview':
						?>
							<grid-cell class="grid-head-label">
								<h2 class="description">Phila Code Challenge</h2>
							</grid-cell>					
							<grid-cell class="content">
								<h2 class="description">Tabs contain screenshots of what the code builds with this plugin</h2>

								<h3 class="description">The Challenge</h3>
								<p>
									Often, we need to accommodate requests from users for a new field, or new section in the WordPress admin interface, with a corresponding rendering on the front-end. 
								</p>
								<p>
								Your task is to create a set of fields in the WordPress admin that allows a user to add the following to the <b>posts</b> post type in WordPress:</p>


								<ul>
									<li> A date field to select the “release date” of the post</li>
									<li>A set of repeatable text fields for:
</ul>
								<ul>
									<li>Contact name</li>
									<li>Contact email addresse</li>
									<li>Contact phone number</li>
								</ul>
								<p>
									Render something like this:
								<img src="<?php echo plugins_url( 'images/frontend-render.png', dirname( __DIR__ ) ); ?>" width="95%" />
								</p>
								<p>These fields should be rendered on the front-end under the post title (see the highlighted area of the next slide for an example). You can modify the WordPress default Twenty Nineteen (or Twenty Twenty) theme.</p>
								
							</grid-cell>
						<?php
						break;
				}
			}
			?>
		</div>
	</div>
		<?php
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
