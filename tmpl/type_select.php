<?php 

/**
 * @package E-Search
 * Template file for object type select field
**/
 
 ?>

<select name="type">
		<option value="post"><?php _e( 'Select type' ); ?></option>
		<option value="post" <?php if($_GET["type"] == "post") echo "selected=selected"; ?>>Post</option>
		<option value="page" <?php if($_GET["type"] == "page") echo "selected=selected"; ?>>Page</option>
		<option value="comment" <?php if($_GET["type"] == "comment") echo "selected=selected"; ?>>Comment</option>
</select>