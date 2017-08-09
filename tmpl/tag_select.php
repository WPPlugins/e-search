<?php 

/**
 * @package E-Search
 * Template file for tag select field
**/
 
 ?>

<select name="etag">
		<option value=""><?php _e( 'Select tag' ); ?></option>
	<?php foreach($tag_list as $tag) : ?>
		<option value="<?php echo $tag->term_id; ?>" <?php if($_GET["etag"] == $tag->term_id) echo "selected=selected"; ?>><?php echo $tag->name; ?></option>
	<?php endforeach; ?>

</select>