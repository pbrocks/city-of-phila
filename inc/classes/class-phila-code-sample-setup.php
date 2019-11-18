<?php

new Phila_Code_Sample_Setup();
class Phila_Code_Sample_Setup {
	public function __construct() {
		add_filter( 'admin_footer_text', array( $this, 'phila_change_admin_footer' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'phila_enqueue_frontend_scripts_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'phila_enqueue_dashboard_scripts_styles' ) );
		add_filter( 'rwmb_meta_boxes', array( $this, 'cphila_contact_info_meta_box' ) );
		add_filter( 'body_class', array( $this, 'add_phila_body_class' ) );
		add_filter( 'the_content', array( $this, 'cphila_contact_info_filter' ) );

	}
	/**
	 * [phila_change_admin_footer]
	 *
	 * Adding some info to footer of Welcome Page
	 *
	 * @param  [type] $footer_text [description]
	 * @return [type]              [description]
	 */
	public function phila_change_admin_footer( $footer_text ) {
		global $code_sample_page;
		$screen_check = get_current_screen();
		if ( $code_sample_page == $screen_check->id ) {
			return '<span id="footer-note">Deliciously delivered by <a href="https://github.com/pbrocks/phila-code-sample" target="_blank">pbrocks</a>. | ♥ | Code located on your server at <span style="color:slategray;">' . __FILE__ . '</span></span><br>';
		} else {
			return $footer_text;
		}
	}

	public function phila_enqueue_dashboard_scripts_styles() {
		wp_register_style( 'plugin-dash', plugins_url( 'css/dashboard-customization.css', __DIR__ ) );
		wp_enqueue_style( 'plugin-dash' );
	}

	public function phila_enqueue_frontend_scripts_styles() {
		wp_register_style( 'plugin-frontend', plugins_url( 'css/frontend-customization.css', __DIR__ ) );
		wp_enqueue_style( 'plugin-frontend' );
	}

	public function cphila_contact_info_meta_box( $meta_boxes ) {
		$prefix = 'cphila_';

		$meta_boxes[] = array(
			'id'         => 'fictional_event',
			'title'      => esc_html__( 'Fictional Event Metabox', 'city-of-phila' ),
			'post_types' => array( 'post' ),
			'context'    => 'normal',
			'priority'   => 'default',
			'autosave'   => 'true',
			'fields'     => array(
				array(
					'id'         => $prefix . 'release_date',
					'type'       => 'date',
					'name'       => esc_html__( 'Release Date', 'city-of-phila' ),
					'js_options' => array(),
					'attributes' => array(),
				),
				array(
					'type'    => 'fieldset_text',
					'id'      => $prefix . 'contact_info',
					'clone'   => true,
					'options' => array(

						'name'  => __( 'Contact Name', 'city-of-phila' ),
						'url'   => __( 'Contact URL', 'city-of-phila' ),
						'email' => __( 'Contact Email', 'city-of-phila' ),
						'phone' => __( 'Contact Phone', 'city-of-phila' ),
					),
					'clone'   => true,
				),
			),
		);

		return $meta_boxes;
	}

	public function add_phila_body_class( $classes ) {
		$classes[] = 'phila-code';
		return $classes;
	}

	public function cphila_contact_info_filter( $content ) {
		wp_enqueue_style( 'phila-code-2019' );

		if ( 'post' !== get_post_type( get_the_ID() ) ) {
			return $content;
		}

		$release_date      = rwmb_meta( 'cphila_release_date' );
		$prepended_content = ( $release_date ? date_i18n( get_option( 'date_format' ), strtotime( $release_date ) ) : '' );
		if ( ! empty( $prepended_content ) ) {
			$return_date = '<p><b>Release date: </b>' . $prepended_content . '</p>';
		} else {
			$return_date = '';
		}
		$field_values = rwmb_meta( 'cphila_contact_info' );

		if ( ! empty( $field_values ) ) {
			ob_start();

			$current_theme = wp_get_theme();
			if ( 'Twenty Nineteen' === $current_theme->get( 'Name' ) ) {
				echo '<style>
			#phila-wrapper {
				font-size: .8rem;
			}
			.entry .entry-content > * {
			max-width: calc(12 * (100vw / 12) - 28px);
			}
			</style>';
			}
			foreach ( $field_values as $value ) {
				printf(
					'<div class="%1$s">
			<p><a href="%4$s">%2$s</a></p>
			<p>%3$s</p></div>',
					'phila-contact',
					( $value['name'] ? '<b>' . __( 'Contact Name: ', 'city-of-phila' ) . '</b>' . esc_attr( $value['name'] ) : '' ),
					( $value['email'] ? '<b>' . __( 'Contact email: ', 'city-of-phila' ) . '</b>' . esc_html( $value['email'] ) : '' ),
					( $value['email'] ? esc_url( $value['url'] ) : '#' )
				);
			}

			$return_values = ob_get_clean();

			return '<div id="phila-wrapper"><p class="release-date">' . $return_date . '</p><div id="phila-output">' . $return_values . '</div></div>' . $content;
		} else {
			return $content;
		}
	}

}
