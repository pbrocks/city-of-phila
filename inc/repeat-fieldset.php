<?php


add_filter( 'rwmb_meta_boxes', 'cphila_contact_fieldset_metabox' );
function cphila_contact_fieldset_metabox( $meta_boxes ) {
	$prefix = 'cphila_';

	$meta_boxes[] = array(
		'id'      => 'field_id',
		'name'    => 'Fieldset Text',
		'type'    => 'fieldset_text',

		// Options: array of key => Label for text boxes
		// Note: key is used as key of array of values stored in the database
		'options' => array(
			'name'    => 'Name',
			'address' => 'Address',
			'email'   => 'Email',
		),

		// Is field cloneable?
		'clone'   => true,
	);

	return $meta_boxes;
}
