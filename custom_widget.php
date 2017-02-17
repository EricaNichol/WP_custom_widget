<?php

/*
Plugin Name: Custom Widget
Plugin URI: codingbydave.com
Description: Building a Custom Widget.
Author: David Lin @CodingByDave
Version: 1
Author URI: codingbydave.com
*/


// Creating the widget
class CBD_widget extends WP_Widget {

	function __construct() {
		$widget_options = array(
			'classname'		 => 'example_widget',
			'description'  => 'This is an Example Widget'
		);
		parent::__construct('CBD_widget', 'Category List Widget', $widget_options);
	}

	// Creating widget front-end
	// This is where the action happens
	public function widget( $args, $instance ) {
		$title = strtolower($instance[ 'title' ]);
		$category_args = array(
		    'show_option_all'    => '',
		    'orderby'            => 'name',
		    'order'              => 'ASC',
		    'style'              => 'list',
		    'show_count'         => 0,
		    'hide_empty'         => 1,
		    'use_desc_for_title' => 1,
		    'child_of'           => 0,
		    'feed'               => '',
		    'feed_type'          => '',
		    'feed_image'         => '',
		    'exclude'            => '',
		    'exclude_tree'       => '',
		    'include'            => '',
		    'hierarchical'       => 1,
		    'title_li'           => '',
		    'show_option_none'   => __( '' ),
		    'number'             => null,
		    'echo'               => 0,
		    'depth'              => 0,
		    'current_category'   => 0,
		    'pad_counts'         => 0,
		    'taxonomy'           => $title.'_categories',
		    'walker'             => null
		);


		$categories = get_categories($category_args);

		echo $args['before_widget'] . $args['before_title'] . $title . $args['after_title'];
		?>
		<ul class="CBD_cat_list">
			<?php

			foreach ( $categories as $category ):
			$cat_title = $category->name;
			$cat_name  = $category->taxonomy;
			$cat_id    = $category->term_id;
			?>
			<li><a class="cat_list_item item_anchor" href="#term-<?php echo $cat_id; ?>"><?php echo $cat_title; ?></a></li>
			<?php
			// print "<pre>";
			// print_r($post_items);
			// print "</pre>";
			endforeach;
			?>
		</ul>
			<?php
 			echo $args['after_widget'];
	}

	// Widget Backend
	public function form( $instance ) {
		$array = Array();
		foreach ( get_post_types( array('public'=>true, '_builtin'=>false ) ) as $post_type ) {
			$array[] = $post_type;
		}
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}

		// Widget admin form
		?>
		<div>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Content Type:' ); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" >
				<?php foreach($array as $arr): ?>
					<?php if ($title === esc_attr( $arr ) ): ?>
						<option selected="selected" value="<?php echo esc_attr( $arr ); ?>"><?php echo $arr; ?></option>
					<?php else: ?>
						<option value="<?php echo esc_attr( $arr ); ?>"><?php echo $arr; ?></option>
					<?php endif; ?>
				<?php endforeach; ?>
			<select>
		</div>
		<?php
	}

	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		print $instance;
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		return $instance;
	}

} // Class wpb_widget ends here

// Register and load the widget
function wpb_load_widget() {
	register_widget( 'CBD_widget' );
}
add_action( 'widgets_init', 'wpb_load_widget' );
