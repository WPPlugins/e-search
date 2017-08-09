<?php 

/**
 * @package E-Search
 * Template file for tag select field
**/
 
 ?>

<select name="eauthor">
		<option value=""><?php _e( 'Select author' ); ?></option>
	<?php foreach($author_list as $author) : ?>
		<option value="<?php echo $author->ID; ?>" <?php if($_GET["eauthor"] == $author->ID) echo "selected=selected"; ?>><?php echo $author->user_nicename; ?></option>
	<?php endforeach; ?>

</select>