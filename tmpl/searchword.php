<?php 

/**
 * @package E-Search
 * Template file for searchword field
**/
 
 ?>

<input name="searchword" class="field inputbox" type="text" value="<?php if($_GET["searchword"] != '') echo $_GET["searchword"]; else echo "Searchword"; ?>" />