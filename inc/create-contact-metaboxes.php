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
				'type'            => 'fieldset_text',
				'id'              => $prefix . 'contact_info',
				'clone'           => true,
				'group_title'     => 'Contact {#} Info',
				// 'options'     =>
				// array(
				// 'id'      => 'field_id',
				// 'name'    => 'Fieldset Text',
				// 'type'    => 'fieldset_text',
				// Options: array of key => Label for text boxes
				// Note: key is used as key of array of values stored in the database
						'options' => array(
							'name'    => 'Name',
							'address' => 'Address',
							'email'   => 'Email',
						),

				// Is field cloneable?
				'clone'           => true,
			),
		),
	);

	return $meta_boxes;
}




add_filter( 'the_content', 'cphila_post_content_filter' );
function cphila_post_content_filter( $content ) {
	if ( 'post' !== get_post_type( get_the_ID() ) ) {
		return $content;
	}
		$prepended_content = '<div style="color:red">Filtering ' . wp_strip_all_tags( $content ) . ' with ' . __FUNCTION__ . '</div>';
	$return_content        = $prepended_content . $content;
	return $return_content;
}
add_filter( 'the_content', 'filter_the_content_in_the_main_loop' );
function filter_the_content_in_the_main_loop( $content ) {
	if ( get_post_type( get_the_ID() ) === 'post' ) {
		$meta_fields = get_post_custom();

		$content .= esc_html__( "I'm filtering a post on ", 'city-of-phila' );
		$content .= date_i18n( get_option( 'date_format' ), strtotime( $meta_fields['cphila_release_date'][0] ) );
	} else {
		$content .= esc_html__( 'NOT filtering a post', 'city-of-phila' );
	}

	return $content;
}


add_filter( 'the_content', 'cphila_postmeta_content_filter' );
function cphila_postmeta_content_filter( $content ) {
	if ( 'post' !== get_post_type( get_the_ID() ) ) {
		return $content;
	}

	$meta_fields = get_post_custom();
	return $content . '<pre>' . print_r( $meta_fields, true ) . '</pre>';
}
