<?php

class Featured_Posts_via_API extends WP_Widget {

	/**
	 * Sets up a new Featured Posts widget instance.
	 *
	 * @since 2.8.0
	 */
	public function __construct() {
		$widget_ops = array(
			'classname'                   => 'widget_featured_entries',
			'description'                 => __( 'WP Engine&#8217;s  Featured Posts.', 'phila-code-sample' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'featured-posts', __( 'Featured on WP Engine&#8217;s Blog' ), $widget_ops );
		$this->alt_option_name = 'widget_featured_entries';
	}

	/**
	 * Outputs the content for the current Featured Posts widget instance.
	 *
	 * @since 2.8.0
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Featured Posts widget instance.
	 */
	public function widget( $args, $instance ) {
		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Featured on WP Engine&#8217;s Blog', 'phila-code-sample' );

		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
		if ( ! $number ) {
			$number = 5;
		}
		$show_date    = isset( $instance['show_date'] ) ? $instance['show_date'] : false;
		$show_excerpt = isset( $instance['show_excerpt'] ) ? $instance['show_excerpt'] : false;

		$request = wp_remote_get( 'https://torquemag.io/wp-json/wp/v2/posts?per_page=100' );

		if ( is_wp_error( $request ) ) {
			return false;
		}
		$body    = wp_remote_retrieve_body( $request );
		$cat_obj = json_decode( $body );

		foreach ( $cat_obj as $key => $value ) {
			if ( in_array( 44, $value->categories ) ) {
				// echo '<br><pre>';
				$stg[ $key ]['slug']       = $value->slug;
				$stg[ $key ]['id']         = $value->id;
				$stg[ $key ]['categories'] = $value->categories;
				$stg[ $key ]['link']       = $value->link;
				$stg[ $key ]['title']      = $value->title->rendered;
			}
		}

		?>
		<?php echo $args['before_widget']; ?>
		<?php
		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
		$num = 0;
		foreach ( $cat_obj as $key => $post ) {
			if ( $num < $number && in_array( 44, $post->categories ) ) {
				echo '<a href="' . $post->link . '"><h4>' . $post->title->rendered . '</h4></a>';
				if ( $show_date ) :
					?>
						<span class="post-date"><?php echo get_the_date( '', $post->ID ); ?></span>
					<?php
				endif;
				if ( $show_excerpt ) {
					echo $post->excerpt->rendered;
				}
				$num++;
			}
		}
		echo $args['after_widget'];
	}

	/**
	 * Handles updating the settings for the current Featured Posts widget instance.
	 *
	 * @since 2.8.0
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance                 = $old_instance;
		$instance['title']        = sanitize_text_field( $new_instance['title'] );
		$instance['number']       = (int) $new_instance['number'];
		$instance['show_date']    = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;
		$instance['show_excerpt'] = isset( $new_instance['show_excerpt'] ) ? (bool) $new_instance['show_excerpt'] : false;
		return $instance;
	}

	/**
	 * Outputs the settings form for the Featured Posts widget.
	 *
	 * @since 2.8.0
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		$title        = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$number       = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		$show_date    = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
		$show_excerpt = isset( $instance['show_excerpt'] ) ? (bool) $instance['show_excerpt'] : false;
		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'phila-code-sample' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:', 'phila-code-sample' ); ?></label>
		<input class="tiny-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" /></p>

		<p><input class="checkbox" type="checkbox"<?php checked( $show_date ); ?> id="<?php echo $this->get_field_id( 'show_date' ); ?>" name="<?php echo $this->get_field_name( 'show_date' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'show_date' ); ?>"><?php _e( 'Display post date?', 'phila-code-sample' ); ?></label></p>
		<p><input class="checkbox" type="checkbox"<?php checked( $show_excerpt ); ?> id="<?php echo $this->get_field_id( 'show_excerpt' ); ?>" name="<?php echo $this->get_field_name( 'show_excerpt' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'show_excerpt' ); ?>"><?php _e( 'Display excerpt?', 'phila-code-sample' ); ?></label></p>
		<?php
	}
}

add_action( 'widgets_init', 'initialize_featured_posts_via_api' );
function initialize_featured_posts_via_api() {
	return register_widget( 'Featured_Posts_via_API' );
}
