<?php 

/**
 * @package E-Search
 * Template file for post title a-z field
**/
 
 ?>

<script>
	jQuery(document).ready(function() {
		var title_az = jQuery("input[name=title_az]").val();
		jQuery("a.title_az").each(function() {
			if(title_az == jQuery(this).text()) {
				jQuery(this).addClass("active");
			}
		});
	
		jQuery("a.title_az").click(function() {
			if(jQuery(this).hasClass("active") == 0) {
				jQuery("a.title_az").removeClass("active");
				jQuery(this).addClass("active");
				jQuery("input[name=title_az]").val(jQuery(this).text());
			}
			else {
				jQuery(this).removeClass("active");
				jQuery("input[name=title_az]").val("");
			}
			return false;
		});
	});
</script>
	
	<div class="title-az-container">
		<?php foreach(range('a', 'z') as $letter) : ?>
			<a class="title_az" href="#"><?php echo $letter; ?></a>
		<?php endforeach; ?>		
		<br />
		<?php foreach(range(1, 9) as $num) : ?>
			<a class="title_az" href="#"><?php echo $num; ?></a>
		<?php endforeach; ?>
	</div>
		
	<input name="title_az" type="hidden" value="<?php echo $_GET["title_az"]; ?>" />
