<?php
/**
 * Create Metaboxes
 * Keeping files distinct according to their function is a way of
 * maintaining sanity for developers. If there is an issue with a
 * given file, its functions can be turned of in an instant by commenting out the line of code in the base file that includes this file in the plugin.
 */
add_filter( 'rwmb_meta_boxes', 'cphila_contact_info_meta_box' );
function cphila_contact_info_meta_box( $meta_boxes ) {
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

add_filter( 'body_class', 'add_phila_body_class' );
function add_phila_body_class( $classes ) {
	$classes[] = 'phila-code';
	return $classes;
}

add_filter( 'the_content', 'cphila_contact_info_filter' );
function cphila_contact_info_filter( $content ) {
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
