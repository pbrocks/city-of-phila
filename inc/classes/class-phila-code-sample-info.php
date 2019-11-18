<?php

// new Phila_Code_Sample_Info();
class Phila_Code_Sample_Info {
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'initialize_template_request_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'initialize_template_request_scripts' ) );
		add_action( 'init', array( $this, 'phila_admin_init' ) );
	}
	/**
	 * [phila_code_sample_footer]
	 *
	 * @return [type] [description]
	 */
	public function phila_code_sample_footer() {
		echo '<div id="' . preg_replace( '/_+/', '-', __FUNCTION__ ) . '">';
		echo '<h3>' . __FILE__ . __LINE__ . '</h3>';
		echo '</div>';
	}

	/**
	 * [initialize_template_request_scripts]
	 *
	 * @return [type] [description]
	 */
	public function initialize_template_request_scripts() {
		wp_register_style( 'phila-code-sample', plugins_url( 'css/phila-code-sample.css', __DIR__ ), time() );
		wp_enqueue_style( 'phila-code-sample' );
	}

	public function phila_admin_init() {
		$settings = get_option( 'phila_tabbed_settings' );
		if ( empty( $settings ) ) {
			$settings = array(
				'phila_intro'     => 'Some intro text for the home page',
				'phila_tag_class' => false,
				'phila_ga'        => false,
			);
			add_option( 'phila_tabbed_settings', $settings, '', 'yes' );
		}
	}

	public function phila_admin_tabs( $current = 'overview' ) {
		$tabs  = array(
			'overview'   => 'Overview',
			'background' => 'Background',
			'scenario1a' => 'Scenario 1a',
			'scenario1b' => 'Scenario 1b',
			'scenario2'  => 'Scenario 2',
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
								<p>Although there were two questions, the first has two parts itself, so I split them up. The following three (3) tabs will address each task individually.</p>
								<p>To begin, however, I want to mention that I built this plugin so that when it is activated, the user is taken to this welcome screen with five (5) tabs. The plugin alters the background color of the dashboard, signalling that the plugin is active. I find this type of diagnostic useful when developing code, and remove for production.</p>
								<p>Since the theme of this challenge is to assist authors, I also placed a bouncing arrow to indicate where the author could go to find more help on a topic. If a user closes the arrow by clicking on the X, the action is recorded in usermeta and won't show up again.</p>
							</grid-cell>
						<?php
						break;
					case 'scenario1a':
						?>
							<grid-cell class="grid-head-label">
								<h2 class="description">Scenario 1a</h2>
							</grid-cell>						
							<grid-cell class="content">
							<p class="description">Task 1a: 
								 <strong>Write a plugin that lets authors easily see which template a page is using</strong> and also see only pages using a particular template.
							</p>
							<p>For authors to quickly see what template a page is using, I added a node to the WP Admin Bar that shows the full path of the template. I did this in case a plugin is creating the page template. Note: some themes don't use page templates as extensively as before in WordPress. In fact, the default Twentynineteen theme doesn't have any page templates, but I added one called <em>Second</em> for demonstration purposes. This screencast shows the interface for authors on the frontend.</p>
							<div><img src="<?php echo plugins_url( 'images/page-templates.gif', dirname( __DIR__ ) ); ?>" />
							</div>
							<p>If this welcome screen were converted to a help area, we could have a dropdown selector to show the template used for each individual page, such as:</p>
							<p>
						<?php
						$args = array(
							'echo'             => 1,
							'name'             => 'page_id',
							'id'               => 'page_id',
							'show_option_none' => 'Select a Page',
						);
						?>
	<form id="get-template-form" method="post">
						<?php wp_dropdown_pages( $args ); ?>
	</form>
	<div id="return-page-template"></div>
								</p>
							<p>Again, this may not be very revealing if the current theme isn't using page templates.</p>
							</grid-cell>
							<?php
						break;
					case 'scenario1b':
						?>
							<grid-cell class="grid-head-label">
								<h2 class="description">Scenario 1b</h2>
							</grid-cell>						
							<grid-cell class="content">
								<p class="description">Task 1b: Write a plugin that lets authors easily see which template a page is using and also see <strong>only pages using a particular template</strong>.
								</p>
								<p>For this part of the task, I first checked the which pages were using a page template by checking for the meta key <em>_wp_page_template</em>, like this<pre>
	$args  = array(
		'meta_key'    => '_wp_page_template',
		'meta_value'  => '',
		'post_type'   => 'page',
		'post_status' => 'publish',
	);
	$pages = get_pages( $args );
	</pre>
	which gives me an array like this:
	<pre>
	$page_templates used Array
	(
		[0] => fluid-width.php
		[1] => page-second.php
		[2] => page-second.php
		[3] => interstitial.php
		[4] => page-second.php
	)
	</pre>
Your setup will render differently based on the values of your post meta. The following code will show what page template you have in your set up.</p>
							<?php
							$args  = array(
								'meta_key'    => '_wp_page_template',
								'meta_value'  => '',
								'post_type'   => 'page',
								'post_status' => 'publish',
							);
							$pages = get_pages( $args );
							foreach ( $pages as $key => $page_value ) {
								$page                                = get_post( $page_value );
								$iidd                                = $page->ID;
								$this_page[ $iidd ]['id']            = $page->ID;
								$this_page[ $iidd ]['page_template'] = $page->meta_value;
								$this_page[ $iidd ]['post_title']    = $page->post_title;
								$page_templates[]                    = basename( $page->meta_value );
							}
							echo '<pre>$page_templates used ';
							print_r( $page_templates );
							echo '</pre>';
							?>
							<p>Looking at all of the pages on your site, you will see the page id, page name, and the page template used. If the default is used, the following code will return <em>page.php</em></p>
							<?php
							echo '<pre>$this_page used ';
							echo $this_page[2]['page_template'];
							print_r( $this_page );
							echo '</pre>';
							$all_pages = get_pages();
							foreach ( $all_pages as $key => $page_value ) {
								if ( isset( $this_page[ $page_value->ID ]['page_template'] ) ) {
									$tmpl = basename( $this_page[ $page_value->ID ]['page_template'] );
								} else {
									$tmpl = 'page.php';
								}
								echo '<h4>' . $page_value->ID . ' | ' . $page_value->post_title . ' | ' . $tmpl . '</h4>';
							}
							?>
							</grid-cell>
								<?php
						break;
					case 'scenario2':
						?>
							<grid-cell class="grid-head-label">
								<h2 class="description">Phila Code Challenge</h2>
							</grid-cell>						
							<grid-cell class="content">
								<h3>Scenario two</h3>
								<p>Display 5 most recent featured posts out of the REST API.</p>
								<p>To display Featured Posts from other blogs, I used the REST API to get posts from the Torque Mag site.</p>
<pre>	$request = wp_remote_get( 'https://torquemag.io/wp-json/wp/v2/posts?per_page=100' );

	if ( is_wp_error( $request ) ) {
		return false;
	}
	$body    = wp_remote_retrieve_body( $request );
	$cat_obj = json_decode( $body );
</pre>
								<p>I checked for the category of <em>Featured</em> and only rendered from there. The result looks like this:</p>
							<div><img width="88%" src="<?php echo plugins_url( 'images/widget-frontend.png', dirname( __DIR__ ) ); ?>" />
							</div>
							<p>On the backend, you can select the number of posts to show, the default being 5, as well as choose to show the date and/or excerpt of the post.</p>
							<div><img width="88%" src="<?php echo plugins_url( 'images/widget-backend.png', dirname( __DIR__ ) ); ?>" />
							</grid-cell>
						<?php
						break;
					case 'overview':
						?>
							<grid-cell class="grid-head-label">
								<h2 class="description">Phila Code Challenge</h2>
							</grid-cell>					
							<grid-cell class="content">
								<ol>
									<li>Marketing team members are having a hard time figuring out what page templates are being used on what pages. Write a plugin that lets authors easily see which template a page is using and also see only pages using a particular template.</li>

									<li>The stakeholders want to be able to get featured posts out of many of our blogs and online magazines so they can put the posts into a widget on the philangine.com blog. Write a plugin for those WordPress sites that gives the post author a way to mark a post as "Featured on WP Engine's blog" and a way to get the 5 most recent featured posts out of the REST API.</li>
								</ol>
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

}
