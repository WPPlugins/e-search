<?php
/**
 * @package E-Search
 */
class ESearch_Widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'esearch_widget',
			__( 'E-Search' ),
			array( 'description' => __( 'Displays extended search form' ) )
		);

		if ( is_active_widget( false, false, $this->id_base ) ) {
			add_action('wp_enqueue_scripts', array($this, 'script_head_additions'));
			add_action('wp_enqueue_scripts', array($this, 'style_head_additions'));
		}
	}

	function script_head_additions() {

		wp_enqueue_script( 'esearch', plugins_url( '/assets/js/esearch.js', __FILE__ ), array('jquery','jquery-ui-core','jquery-ui-datepicker'));

	}
	
	function style_head_additions() {
	
		wp_enqueue_style( 'esearch', plugins_url( '/assets/css/style.css', __FILE__ ) );
		wp_enqueue_style( 'jquery.ui.theme', plugins_url( '/assets//css/jquery-ui-smoothness/jquery-ui-1.8.23.custom.css', __FILE__ ) );

	}

	function form( $instance ) {
		if ( $instance ) {
			$title = esc_attr( $instance['title'] );
			$pre_text = esc_attr( $instance['pre_text'] );
			
			$searchword = esc_attr( $instance['searchword'] );
			$title_az = esc_attr( $instance['title_az'] );
			$category_select = esc_attr( $instance['category_select'] );
			$tag_select = esc_attr( $instance['tag_select'] );
			$author_select = esc_attr( $instance['author_select'] );
			$date_select = esc_attr( $instance['date_select'] );
			$type_select = esc_attr( $instance['type_select'] );
		}
?>

		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
		</p>		
		
		<p>
		<label for="<?php echo $this->get_field_id( 'pre_text' ); ?>"><?php _e( 'Text before form:' ); ?></label> 
		<textarea class="widefat" id="<?php echo $this->get_field_id( 'pre_text' ); ?>" name="<?php echo $this->get_field_name( 'pre_text' ); ?>"><?php echo $pre_text; ?></textarea>
		</p>		
		
		<table class="param_table" cellpadding="0" cellspacing="0" border="0" width="100%">
		
			<tr>
				<td><?php _e( 'Show searchword input?' ); ?></td>
				<td>
					<label><input type="radio" name="<?php echo $this->get_field_name( 'searchword' ); ?>" value="0" <?php if($searchword == 0) : ?>checked="checked"<?php endif; ?>><?php _e( 'No' ); ?></label>
					
					<label><input type="radio" name="<?php echo $this->get_field_name( 'searchword' ); ?>" value="1" <?php if($searchword == 1) : ?>checked="checked"<?php endif; ?>><?php _e( 'Yes' ); ?></label>
				</td>
			</tr>
		
			<tr>
				<td><?php _e( 'Show post title a-z select?' ); ?></td>
				<td>
					<label><input type="radio" name="<?php echo $this->get_field_name( 'title_az' ); ?>" value="0" <?php if($title_az == 0) : ?>checked="checked"<?php endif; ?>><?php _e( 'No' ); ?></label>
					
					<label><input type="radio" name="<?php echo $this->get_field_name( 'title_az' ); ?>" value="1" <?php if($title_az == 1) : ?>checked="checked"<?php endif; ?>><?php _e( 'Yes' ); ?></label>
				</td>
			</tr>
		
			<tr>
				<td><?php _e( 'Show category select?' ); ?></td>
				<td>
					<label><input type="radio" name="<?php echo $this->get_field_name( 'category_select' ); ?>" value="0" <?php if($category_select == 0) : ?>checked="checked"<?php endif; ?>><?php _e( 'No' ); ?></label>
					
					<label><input type="radio" name="<?php echo $this->get_field_name( 'category_select' ); ?>" value="1" <?php if($category_select == 1) : ?>checked="checked"<?php endif; ?>><?php _e( 'Yes' ); ?></label>
				</td>
			</tr>
			
			<tr>
				<td><?php _e( 'Show tag select?' ); ?></td> 
				<td>
					<label><input type="radio" name="<?php echo $this->get_field_name( 'tag_select' ); ?>" value="0" <?php if($tag_select == 0) : ?>checked="checked"<?php endif; ?>><?php _e( 'No' ); ?></label>
					
					<label><input type="radio" name="<?php echo $this->get_field_name( 'tag_select' ); ?>" value="1" <?php if($tag_select == 1) : ?>checked="checked"<?php endif; ?>><?php _e( 'Yes' ); ?></label>
				</td>
			</tr>
			
			<tr>
				<td><?php _e( 'Show author select?' ); ?></td> 
				<td>
					<label><input type="radio" name="<?php echo $this->get_field_name( 'author_select' ); ?>" value="0" <?php if($author_select == 0) : ?>checked="checked"<?php endif; ?>><?php _e( 'No' ); ?></label>
					
					<label><input type="radio" name="<?php echo $this->get_field_name( 'author_select' ); ?>" value="1" <?php if($author_select == 1) : ?>checked="checked"<?php endif; ?>><?php _e( 'Yes' ); ?></label>
				</td>
			</tr>
			
			<tr>
				<td><?php _e( 'Show date select?' ); ?></td> 
				<td>
					<label><input type="radio" name="<?php echo $this->get_field_name( 'date_select' ); ?>" value="0" <?php if($date_select == 0) : ?>checked="checked"<?php endif; ?>><?php _e( 'No' ); ?></label>
					
					<label><input type="radio" name="<?php echo $this->get_field_name( 'date_select' ); ?>" value="1" <?php if($date_select == 1) : ?>checked="checked"<?php endif; ?>><?php _e( 'Yes' ); ?></label>
				</td>
			</tr>
			
			<tr>
				<td><?php _e( 'Show type select?' ); ?></td> 
				<td>
					<label><input type="radio" name="<?php echo $this->get_field_name( 'type_select' ); ?>" value="0" <?php if($type_select == 0) : ?>checked="checked"<?php endif; ?>><?php _e( 'No' ); ?></label>
					
					<label><input type="radio" name="<?php echo $this->get_field_name( 'type_select' ); ?>" value="1" <?php if($type_select == 1) : ?>checked="checked"<?php endif; ?>><?php _e( 'Yes' ); ?></label>
				</td>
			</tr>
		</table>

<?php 
	}

	function update( $new_instance, $old_instance ) {
		$instance['title'] = $new_instance['title'];
		$instance['pre_text'] = $new_instance['pre_text'];
		
		$instance['searchword'] = $new_instance['searchword'];
		$instance['title_az'] = $new_instance['title_az'];
		$instance['category_select'] = $new_instance['category_select'];
		$instance['tag_select'] = $new_instance['tag_select'];
		$instance['author_select'] = $new_instance['author_select'];
		$instance['date_select'] = $new_instance['date_select'];
		$instance['type_select'] = $new_instance['type_select'];
		
		return $instance;
	}

	function widget( $args, $instance ) {

		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'];
			echo esc_html( $instance['title'] );
			echo $args['after_title'];
		}
		
		if ( ! empty( $instance['pre_text'] ) ) {
			echo $instance['pre_text'];
		}

		require(dirname( __FILE__ ) . "/widget_template.php");
			
		echo $args['after_widget'];
	}
	
}

function esearch_register_widgets() {
	register_widget( 'ESearch_Widget' );
}

add_action( 'widgets_init', 'esearch_register_widgets' );
