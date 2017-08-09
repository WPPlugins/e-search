<?php
/**
 * @package E-Search
 */
/*
Plugin Name: E-Search
Plugin URI: http://wpcar.net
Description: Extended Search functionalify for WordPress
Version: 1.0
Author: Myasoedov Andrey
Author URI: http://wpcar.net
License: GPLv2 or later
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo "Hi there!  I'm just a plugin, not much I can do when called directly.";
	exit;
}

include_once dirname( __FILE__ ) . '/widget.php';

/**
* Search API
* An API that is loaded when a search related action is taken place. The class contains various functions for outputing to WordPress and doing the necessary legwork for
* common search functions (pagination, trimming content for display, options and filtering, etc)
* @version 1.0.8
*/
class esearch_api
{
	
	function esearch_wrapper() {
		global $wp_query;
		
		$this->flags = $_GET;
		if(!$this->flags["pg"]) {
			$this->flags["pg"] = 1;
		}
		
		// set "the loop" to nothing
		$wp_query->posts = NULL;
		
		// Create a blank "result set" for the wordpress query object
		$object = new stdClass();
		$object->post_title = __( "Search results" );
		$object->post_content = "[results]";
		
		add_shortcode( 'results', array( &$this, 'search' ) );
		
		$this->search();
			
		$wp_query->posts[0] = $object;	

			include(TEMPLATEPATH . "/index.php");

		exit;
	}
	
		function search()
		{
			// Query the database and format results
			$total = $this->find_results();
			
			if( $this->flags['pg'] > 1 )
				$start = intval ( ( ( $this->flags['pg'] - 1 ) * get_option( 'posts_per_page' ) ) + 1 );
			else
				$start = 1;
				
			// Return the results
			if( $total > 0 )
			{
				// FILTER: search_results_start Starts the results output
				$result_html = apply_filters( 'search_results_start', "<ol class=\"searchresults\" start=\"{$start}\">\n" );

				foreach( $this->results as $results ) {
					
					// Result is a post
					if( $results->type == "post" ) {
						$categories_list = get_the_category_list( ', ', '', $results->id );
						$tag_list = get_the_tag_list( '', ', ', '', $results->id );
					
						$tmpl = "
						<li>
							<a href=\"".get_permalink( $results->id )."\" class='result_title'>".$results->title."</a>
							<p class=\"result_summary\">".$this->trim_excerpt($results->content)."
								<br />
								<span class='entry-meta'>
									<span class='cat-links'>Posted in ".$categories_list."</span>
									<span class='sep'> | </span>
									<span class='tag-links'>Tagged ".$tag_list."</span>
								</span>
							</p>
						</li>
						";
					
						$result_html .= apply_filters('search_post_result', $tmpl);
					}
					// Result is a page
					elseif( $results->type == "page" ) {
						$result_html .= apply_filters( 'search_page_result', "<li><strong class='result_type'>" . __( 'Page ' ) . "</strong>: <a href=\"" . get_permalink ( $results->id ) . "\" class='result_title'>" . $results->title . "</a>\n<p class=\"result_summary\">". $this->trim_excerpt( $results->content ) ."</p></li>" );
					}
					// Result is a comment
					elseif( $results->type == "comment" ) {
						$result_html .= apply_filters( 'search_comment_result', "<li><strong class='result_type'>" . __( 'Comment ' ) . "</strong>: <a href=\"" . get_comment_link( $results->id ) . "\" class='result_title'>" . get_the_title( $results->id ) ."</a>\n<p class=\"result_summary\">" . $this->trim_excerpt( $results->content ) ."</p></li>" );
					}
				}
				
				// FILTER: search_results_start Ends the results output
				$result_html .= apply_filters( 'search_results_end', "</ol>\n" );
			}
			
			// No results error
			else {
				// FILTER: search_no_results Allows you to change the error message when no results are returned
				$result_html .= apply_filters( 'search_no_results', "<h2>" . __( ' There are no results for this seach.' ) . "</h2>" );
			}

			// Return the search output
			// FILTER: search_results Allows you to edit the results
			return apply_filters( 'search_results', $result_html . $this->pagination( $total ) );
		}
		
		function find_results()
		{
			global $wpdb;				
			
			$query  = "SELECT * FROM ";
			$query .= "(SELECT p.ID as id, p.post_title as title, p.post_content as content, p.post_author as author, p.post_type as type, p.post_status as status, DATE_FORMAT(p.post_date, '%Y-%m-%d') as date, CONCAT(',', GROUP_CONCAT(tax.term_taxonomy_id), ',') as terms FROM {$wpdb->prefix}posts as p LEFT JOIN {$wpdb->prefix}term_relationships as tax ON tax.object_id = p.ID WHERE p.post_title != '' GROUP BY p.ID ";
			$query .= "UNION ALL ";
			$query .= "SELECT c.comment_post_ID as id, NULL as title, c.comment_content as content, NULL as author, 'comment' as type, pr.post_status as status, DATE_FORMAT(c.comment_date, '%Y-%m-%d') as date, NULL as terms FROM {$wpdb->prefix}comments as c LEFT JOIN {$wpdb->prefix}posts AS pr ON pr.ID = c.comment_post_ID) AS result ";
			$query .= "WHERE result.status = 'publish'";
			
			if($this->flags['searchword']) {
				$query .= " AND (result.title LIKE '%{$this->flags['searchword']}%' OR result.content LIKE '%{$this->flags['searchword']}%')";
			}
			if($this->flags['title_az']) {
				$query .= " AND result.title LIKE '{$this->flags['title_az']}%'";
			}
			if($this->flags['ecat']) {
				$query .= " AND result.terms LIKE '%,{$this->flags['ecat']},%'";
			}
			if($this->flags['etag']) {
				$query .= " AND result.terms LIKE '%,{$this->flags['etag']},%'";
			}				
			if($this->flags['eauthor']) {
				$query .= " AND result.author = '{$this->flags['eauthor']}'";
			}
			if($this->flags['date-from'] || $this->flags['date-to']) {
				if($this->flags['date-from'] && $this->flags['date-to']) {
					$query .= " AND result.date >= '{$this->flags['date-from']}'";
					$query .= " AND result.date <= '{$this->flags['date-to']}'";
				}				
				if($this->flags['date-from'] && !$this->flags['date-to']) {
					$query .= " AND result.date >= '{$this->flags['date-from']}'";
				}				
				if(!$this->flags['date-from'] && $this->flags['date-to']) {
					$query .= " AND result.date <= '{$this->flags['date-to']}'";
				}
			}
			if($this->flags['type']) {
				$query .= " AND result.type = '{$this->flags['type']}'";
			}
		
			$cquery  = "SELECT COUNT(*) as total FROM ";
			$cquery .= "(SELECT p.ID as id, p.post_title as title, p.post_content as content, p.post_author as author, p.post_type as type, p.post_status as status, DATE_FORMAT(p.post_date, '%Y-%m-%d') as date, CONCAT(',', GROUP_CONCAT(tax.term_taxonomy_id), ',') as terms FROM {$wpdb->prefix}posts as p LEFT JOIN {$wpdb->prefix}term_relationships as tax ON tax.object_id = p.ID WHERE p.post_title != '' GROUP BY p.ID ";
			$cquery .= "UNION ALL ";
			$cquery .= "SELECT c.comment_post_ID as id, NULL as title, c.comment_content as content, NULL as author, 'comment' as type, pr.post_status as status, DATE_FORMAT(c.comment_date, '%Y-%m-%d') as date, NULL as terms FROM {$wpdb->prefix}comments as c LEFT JOIN {$wpdb->prefix}posts AS pr ON pr.ID = c.comment_post_ID) AS result ";
			$cquery .= "WHERE result.status = 'publish'";

			if($this->flags['searchword']) {
				$cquery .= " AND (result.title LIKE '%{$this->flags['searchword']}%' OR result.content LIKE '%{$this->flags['searchword']}%')";
			}
			if($this->flags['title_az']) {
				$cquery .= " AND result.title LIKE '{$this->flags['title_az']}%'";
			}
			if($this->flags['ecat']) {
				$cquery .= " AND result.terms LIKE '%,{$this->flags['ecat']},%'";
			}
			if($this->flags['etag']) {
				$cquery .= " AND result.terms LIKE '%,{$this->flags['etag']},%'";
			}				
			if($this->flags['eauthor']) {
				$cquery .= " AND result.author = '{$this->flags['eauthor']}'";
			}
			if($this->flags['date-from'] || $this->flags['date-to']) {
				if($this->flags['date-from'] && $this->flags['date-to']) {
					$cquery .= " AND result.date >= '{$this->flags['date-from']}'";
					$cquery .= " AND result.date <= '{$this->flags['date-to']}'";
				}				
				if($this->flags['date-from'] && !$this->flags['date-to']) {
					$cquery .= " AND result.date >= '{$this->flags['date-from']}'";
				}				
				if(!$this->flags['date-from'] && $this->flags['date-to']) {
					$cquery .= " AND result.date <= '{$this->flags['date-to']}'";
				}
			}
			if($this->flags['type']) {
				$cquery .= " AND result.type = '{$this->flags['type']}'";
			}			

			$count = $wpdb->get_results( apply_filters( "search_count_find_results", $cquery ) );

			// how we are ordering the data
			if( $this->flags['sort'] == "alpha" )
				$query .= " ORDER BY title ".$this->flags['sorttype'];
			elseif( $this->flags['sort'] == "date" )
				$query .= " ORDER BY post_date ".$this->flags['sorttype'];
				
			// Add in the pagination data for the LIMIT part of the query
			$query .= " LIMIT " . ( $this->flags['pg'] - 1 ) * get_option( 'posts_per_page' ) . ",".get_option('posts_per_page').";";

			$this->results = $wpdb->get_results( apply_filters( "search_find_results", $query ) );

			return $count[0]->total;
		}
	
	function pagination( $total ) {
	
		if($total <= get_option( 'posts_per_page' )) {
			return;
		}
			
		// Load some required variables such as the total results, the total needed pages and variable place holders.
		if( $total > 0 ) {
			$pages = ceil( $total / get_option( 'posts_per_page' ) );
		}
		
		if( empty ( $_GET['pg'] ) ) {
			$_GET['pg'] = 1;
		}
		
		$pages = $pages ? $pages : 1;
			
		// Grab the current URL (minus the query string) since search pages can have many query strings
		$current = "http" . ( empty( $_SERVER["HTTPS"] ) ? "": ( $_SERVER["HTTPS"]=='on' ) ? "s": "" )."://" . esc_attr__( $_SERVER["HTTP_HOST"] ) . esc_attr__( $_SERVER["REQUEST_URI"] );
		$current = preg_replace( "/&amp;pg=([0-9]+)/i", "", $current );
			
		// Create the previous, next and number links
		if( $_GET['pg'] > 1 ) {
			$previous_link = "<span class=\"searchpglink\"><a href=\"".$current."&amp;pg=".($_GET['pg'] - 1)."\">&lt;</a></span>";
		}
		else {
			$previous_link = "<span class=\"searchpglink\">&lt;</span>";
		}
			
		if( $_GET['pg'] < $pages ) {
			$next_link = "&nbsp;<span class=\"searchpglink\"><a href=\"".$current."&amp;pg=".($_GET['pg'] + 1)."\">&gt;</a></span>";
		}
		else {
			$next_link = "&nbsp;<span class=\"searchpglink\">&gt;</span>";
		}
			
		if( $pages > 1 ) {
			for( $i = 0, $j = $pages - 1; $i <= $j; ++$i ) {
				$pagenum = $i+1;
				$page = ceil( $pagenum );
					
				if ( $pagenum < ( $_GET['pg'] - 4 ) ) {
					$i = $_GET['pg'] - 6;
					continue;
				}
				
				if ( $page == $_GET['pg'] )
					$links .= "&nbsp;<span class=\"searchpgcurrent\">{$page}</span>"; // this is the current page, no need for a link
				else {
					$links .= "&nbsp;<span class=\"searchpglink\"><a href=\"".$current."&amp;pg=".$page."\" title=\"$page\">$page</a></span>";
					if ( $pagenum > ( $_GET['pg'] + 4 ) )
						break;
				}
			}
		}
		
		$links = "<span class='pagination-container'>" . $previous_link . $links . $next_link . "</span>";
		
		// FILTER: search_pagination Allows you to edit the output of the pagination links
		return apply_filters('esearch_pagination', $links);
	}
	
	function trim_excerpt( $content, $chars = 150, $end = "..." ) {	
		//$content = strip_tags(trim($content));
		//$content = mb_substr($content, 0, $chars).$end;
		return $content;
	}
	
}

global $esearch_plugin;

/**
* Load Search
* This function runs the init_search function
* @global object The class object for the above class
*/
function load_esearch( $query ) {
	global $esearch_plugin;
	if( $query->is_search )
		$esearch_plugin->init_esearch();
}

function load_esearch_api( ) {
	global $esearch_plugin;
	$esearch_plugin = new esearch_api();
	
	if($_GET["esearch"] == 1) {
		add_filter( 'template_redirect', array( &$esearch_plugin, 'esearch_wrapper' ) );
	}
	
	if( $esearch_plugin->plugin != NULL ) {
		$esearch_plugin->plugin->flags = $esearch_plugin->create_flags();
		add_filter( 'pre_get_posts', 'load_esearch' );
	}
}

add_action( 'plugins_loaded', 'load_esearch_api' );

// END SEARCH API 


?>