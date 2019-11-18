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




function cphila_release_date_filter( $content ) {
	if ( 'post' !== get_post_type( get_the_ID() ) ) {
		return $content;
	}
	$meta_fields       = get_post_custom();
	$prepended_content = ( date_i18n( get_option( 'date_format' ), strtotime( $meta_fields['cphila_release_date'][0] ) ) ?: '' );
	if ( ! empty( $prepended_content ) ) {
		$return_content = '<p>' . $prepended_content . '</p>' . $content;
	} else {
		$return_content = $content;
	}
	return $return_content;
}


add_filter( 'the_content', 'cphila_contact_info_filter' );
add_filter( 'the_content', 'cphila_release_date_filter' );
function cphila_contact_info_filter( $content ) {
	if ( 'post' !== get_post_type( get_the_ID() ) ) {
		return $content;
	}
	$meta_fields       = get_post_custom();
	$prepended_content = ( date_i18n( get_option( 'date_format' ), strtotime( $meta_fields['cphila_release_date'][0] ) ) ?: '' );
	if ( ! empty( $prepended_content ) ) {
		$return_date = '<p>' . $prepended_content . '</p>';
	} else {
		$return_date = '';
	}
	$field_values = rwmb_meta( 'cphila_contact_info' );

	// foreach ( $field_values as $value ) {
		// echo $value['name'];
		// echo $value['url'];
		// echo $value['email'];
		// echo $value['phone'];
	// }
	$return_values = wp_sprintf( '%s: %l', __( 'Some cool numbers' ), array( 1, 5, 10, 15 ) );

	return '<p>' . $return_date . '</p>' . '<p>' . print_r( $return_values, true ) . '</p>' . $content;
	$meta_fields = get_post_custom();
	return $content . '<pre>' . print_r( $meta_fields, true ) . '</pre>';
}
