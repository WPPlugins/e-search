<?php 

/**
 * @package E-Search
 * Template file for category select field
**/
 
 ?>

<select name="ecat">
		<option value=""><?php _e( 'Select category' ); ?></option>
	<?php foreach($cat_list as $cat) : ?>
		<option value="<?php echo $cat->term_id; ?>" <?php if($_GET["ecat"] == $cat->term_id) echo "selected=selected"; ?>><?php echo $cat->name; ?></option>
	<?php endforeach; ?>

</select>