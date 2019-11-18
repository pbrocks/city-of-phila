<?
/**
 * Repeatable Custom Fields in a Metabox
 * Author: Helen Hou-Sandi
 * https://gist.github.com/helen/1593065
 *
 * From a bespoke system, so currently not modular - will fix soon
 * Note that this particular metadata is saved as one multidimensional array (serialized)
 */
 
function cphila_get_sample_options() {
	$options = array (
		 __( 'Option 1', 'phila-code-sample' ) => 'option1',
		 __( 'Option 2', 'phila-code-sample' ) => 'option2',
		 __( 'Option 3', 'phila-code-sample' ) => 'option3',
		 __( 'Option 4', 'phila-code-sample' ) => 'option4',
	);
	
	return $options;
}

add_action( 'admin_init', 'cphila_add_meta_boxes', 1 );
function cphila_add_meta_boxes() {
	add_meta_box( 'repeatable-fields', __( 'Contact Info', 'phila-code-sample' ), 'cphila_repeatable_meta_box_display', 'post', 'normal', 'default');
}

function cphila_repeatable_meta_box_display() {
	global $post;

	$cphila_contact_group = get_post_meta( $post->ID, 'cphila_contact_group', true );
	// $options = cphila_get_sample_options();

	wp_nonce_field( 'cphila_repeatable_meta_box_nonce', 'cphila_repeatable_meta_box_nonce' );
	?>
	<script type="text/javascript">
	jQuery(document).ready(function( $ ){
		$( '#add-row' ).on('click', function() {
			var row = $( '.empty-row.screen-reader-text' ).clone(true);
			row.removeClass( 'empty-row screen-reader-text' );
			row.insertBefore( '#contact-info-fieldset tbody>tr:last' );
			return false;
		});
	
		$( '.remove-row' ).on('click', function() {
			$(this).parents('tr').remove();
			return false;
		});
	});
	</script>
	 <h2></h2>
	<table id="contact-info-fieldset" width="100%">
	<thead>
		<tr>
			<th width="30%"><?php _e( 'Name', 'phila-code-sample' ); ?></th>
			<th width="30%"><?php _e( 'Email', 'phila-code-sample' ); ?></th>
			<th width="30%"><?php _e( 'URL', 'phila-code-sample' ); ?></th>
			<th width="10%"></th>
		</tr>
	</thead>
	<tbody>
	<?php

	if ( $cphila_contact_group ) :

		foreach ( $cphila_contact_group as $field ) {
			?>
	<tr>
		<td><input type="text" class="widefat" name="name[]" value="
			<?php
			if ( $field['name'] !== '' ) {
				echo esc_attr( $field['name'] );}
			?>
		" /></td>

		<td><input type="text" class="widefat" name="email[]" value="
			<?php
			if ( $field['email'] !== '' ) {
				echo esc_attr( $field['email'] );
			}
			?>
		" /></td>
	
		<td><input type="text" class="widefat" name="url[]" value="
			<?php
			if ( $field['url'] !== '' ) {
				echo esc_attr( $field['url'] );
			} else {
				echo 'https://';
			}
			?>
		" /></td>
	
		<td><a class="button remove-row" href="#"><?php _e( 'Remove', 'phila-code-sample' ); ?></a></td>
	</tr>
			<?php
		}
	else :
		// show a blank one
		?>
	<tr>
		<td><input type="text" class="widefat" name="name[]" /></td>
	
		<td>
			<input type="email" class="widefat" name="email[]" />
		</td>
	
		<td><input type="url" class="widefat" name="url[]" value="https://" /></td>
	
		<td><a class="button remove-row" href="#"><?php _e( 'Remove', 'phila-code-sample' ); ?></a></td>
	</tr>
	<?php endif; ?>
	
	<!-- empty hidden one for jQuery -->
	<tr class="empty-row screen-reader-text">
		<td><input type="text" class="widefat" name="name[]" /></td>
	
		<td>
			<input type="email" class="widefat" name="email[]" />
		</td>
		
		<td><input type="text" class="widefat" name="url[]" value="https://" /></td>
		  
		<td><a class="button remove-row" href="#"><?php _e( 'Remove', 'phila-code-sample' ); ?></a></td>
	</tr>
	</tbody>
	</table>
	
	<p><a id="add-row" class="button" href="#">Add another</a></p>
	<?php
	}

	add_action( 'save_post', 'cphila_repeatable_meta_box_save' );
	function cphila_repeatable_meta_box_save( $post_id ) {
		if ( ! isset( $_POST['cphila_repeatable_meta_box_nonce'] ) ||
		! wp_verify_nonce( $_POST['cphila_repeatable_meta_box_nonce'], 'cphila_repeatable_meta_box_nonce' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$old     = get_post_meta( $post_id, 'cphila_contact_group', true );
		$new     = array();
		$options = cphila_get_sample_options();

		$names  = $_POST['name'];
		$emails = $_POST['email'];
		$urls   = $_POST['url'];

		$count = count( $names );

		for ( $i = 0; $i < $count; $i++ ) {
			if ( $names[ $i ] != '' ) :
				$new[ $i ]['name'] = stripslashes( strip_tags( $names[ $i ] ) );

				if ( in_array( $emails[ $i ], $options ) ) {
					$new[ $i ]['email'] = $emails[ $i ];
				} else {
					$new[ $i ]['email'] = '';
				}

				if ( $urls[ $i ] == 'https://' ) {
					$new[ $i ]['url'] = '';
				} else {
					$new[ $i ]['url'] = stripslashes( $urls[ $i ] ); // and however you want to sanitize
				}
			endif;
		}

		if ( ! empty( $new ) && $new != $old ) {
			update_post_meta( $post_id, 'cphila_contact_group', $new );
		} elseif ( empty( $new ) && $old ) {
			delete_post_meta( $post_id, 'cphila_contact_group', $old );
		}
	}
