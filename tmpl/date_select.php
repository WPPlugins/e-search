<?php 

/**
 * @package E-Search
 * Template file for date select field
**/
 
 ?>

<script type="text/javascript">

	jQuery(document).ready(function() {
			jQuery("input.datepicker").datepicker({ dateFormat: 'yy-mm-dd' });
	});

</script>
 
	<input style="width: 124px !important;" class="datepicker inputbox field" name="date-from" type="text" value="<?php if($_GET["date-from"] != '') echo $_GET["date-from"]; else echo "Date"; ?>" />
	&nbsp; – &nbsp;
	<input style="width: 124px !important;" class="datepicker inputbox field" name="date-to" type="text" value="<?php echo $_GET["date-to"]; ?>" />

