<?php 

/**
 * @package E-Search
**/
 
 ?>

		<div class="esearch-container">
			<form name="esearch" method="GET" action="<?php bloginfo('url'); ?>">
				
				<div class="esearch-table" style="display: table;">
				
					<?php if($instance['searchword'] == 1) : ?>
						<div class="table-row" style="display: table-row">
							<div class="table-cell" style="display: table-cell">
								<?php
									require(dirname( __FILE__ ) . "/tmpl/searchword.php");
								?>
							</div>
						</div>	
					<?php endif; ?>	
					
					<?php if($instance['title_az'] == 1) : ?>
						<div class="table-row" style="display: table-row">
							<div class="table-cell" style="display: table-cell">
								<?php
									require(dirname( __FILE__ ) . "/tmpl/title_az.php");
								?>
							</div>
						</div>	
					<?php endif; ?>
			
					<?php if($instance['category_select'] == 1) : ?>
						<div class="table-row" style="display: table-row">
							<div class="table-cell" style="display: table-cell">
								<?php
								$cat_list = get_categories();
								if(count($cat_list)) {
									require(dirname( __FILE__ ) . "/tmpl/category_select.php");
								}
								?>
							</div>
						</div>	
					<?php endif; ?>
					
					<?php if($instance['tag_select'] == 1) : ?>
						<div class="table-row" style="display: table-row">
							<div class="table-cell" style="display: table-cell">
								<?php 
								$tag_list =	get_tags();
								if(count($tag_list)) {
									require(dirname( __FILE__ ) . "/tmpl/tag_select.php");
								}
								?>
							</div>
						</div>	
					<?php endif; ?>		
					
					<?php if($instance['author_select'] == 1) : ?>
						<div class="table-row" style="display: table-row">
							<div class="table-cell" style="display: table-cell">
								<?php 
								$author_list = get_users('who=authors');
								if(count($author_list)) {
									require(dirname( __FILE__ ) . "/tmpl/author_select.php");
								}
								?>
							</div>
						</div>	
					<?php endif; ?>	
					
					<?php if($instance['date_select'] == 1) : ?>
						<div class="table-row" style="display: table-row">
							<div class="table-cell" style="display: table-cell">
								<?php 
									require(dirname( __FILE__ ) . "/tmpl/date_select.php");
								?>
							</div>
						</div>	
					<?php endif; ?>		
					
					<?php if($instance['type_select'] == 1) : ?>
						<div class="table-row" style="display: table-row">
							<div class="table-cell" style="display: table-cell">
								<?php
									require(dirname( __FILE__ ) . "/tmpl/type_select.php");
								?>
							</div>
						</div>	
					<?php endif; ?>	
					
					<div class="table-row" style="display: table-row">
						<div class="table-cell" style="display: table-cell">
							<input class="button" type="submit" value="<?php _e( 'Submit' ); ?>" />
						</div>
					</div>	
				
				</div>
				
				<input type="hidden" name="esearch" value="1" />
				
			</form>
		</div>