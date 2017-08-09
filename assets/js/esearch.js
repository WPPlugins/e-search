/**
 * @package E-Search
 */

jQuery.noConflict();

jQuery(document).ready(function() {
	jQuery("input[name=searchword]").focusin(function() {
		if(jQuery(this).val() == "Searchword") {
			jQuery(this).val("");
		}
	}).focusout(function() {
		if(jQuery(this).val() == "") {
			jQuery(this).val("Searchword");
		}		
	});
	
	jQuery("input[name=date-from]").focusin(function() {
		if(jQuery(this).val() == "Date") {
			jQuery(this).val("");
		}
	}).focusout(function() {
		if(jQuery(this).val() == "") {
			jQuery(this).val("Date");
		}		
	});

	jQuery("form[name=esearch]").submit(function() {
		if(jQuery("input[name=searchword]").val() == "Searchword") {
			jQuery("input[name=searchword]").val("");
		}		
		if(jQuery("input[name=date-from]").val() == "Date") {
			jQuery("input[name=date-from]").val("");
		}
	});
});