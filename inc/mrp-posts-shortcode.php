<?php /*

**************************************************************************

Copyright (C) 2017 Mathew Moore

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

**************************************************************************/

// Matt's Rest Post Shortcode
function MRP_Rest_Post_Shortcode( $atts ) {
	 
		$siteurl = site_url();
	 
		$a = shortcode_atts( array(
			'count' 			=> '1',
			'url' 				=> $siteurl,
			'columns' 			=> '1',
			'date' 				=> 'true',
			'title' 			=> 'true',
			'img' 				=> 'true',
			'title_size' 		=> '',
			'excerpt' 			=> 'true',
			'excerpt_length' 	=> '30',
			'offset'			=> '',
    ), $atts );

	// Start REST API
	if (empty($a['offset'])) {
		$response = wp_remote_get( $a['url'] . '/wp-json/wp/v2/posts?per_page=' . $a['count'] );
	}
	else {
		$response = wp_remote_get( $a['url'] . '/wp-json/wp/v2/posts?per_page=' . $a['count'] . '&offset=' . $a['offset'] );
	}

	if( is_wp_error( $response ) ) {
		return;
	}

	$posts = json_decode( wp_remote_retrieve_body( $response ) );

	if( empty( $posts ) ) {
		return;
	}
	ob_start ();
	 if( !empty( $posts ) ) {  
		 // Start the main container class
		 echo "<div class='mrp-container'>\n"; 
		 foreach (array_chunk($posts, $a['columns']) as $post) {
			
			echo "<div class='mrp-row'>\n";
			foreach ( $post as $post ) {
				
				// Create the single post wrapper class
				$mrpcolumns = ( empty( $a['columns'] ) ) ? '' : '<div class="mrp-'.$a['columns'].'-column">';
				
				if( $a['date'] == 'true' ) {
				// Check if Date is set to 'True'
				$jsondate = new DateTime( $post->date );
				$humandate = '<p class="mrp-post-date">'.$jsondate->format("F d, Y").'</p>';
				} else $humandate = '';
				
				// Check if "Post Image" is set to "True"
				if( $a['img'] == 'true' ) {
				$post_thumbnail = '<img class="mrp-img" src="'. $post->swp_thumbnail .'"/>';
				} else $post_thumbnail = '';
				
				// Start Excerpt Limiter
				if( $a['excerpt'] == 'true' ) {
					
					$the_excerpt = strip_tags($post->excerpt->rendered);
					$limit = null; $separator = null;
					// Set standard words limit
					if (is_null($limit)){
						$excerpt = explode(' ', $the_excerpt, $a['excerpt_length']+1);
					} else { $excerpt = explode(' ', $the_excerpt, $limit); }

					// Set standard separator
					if (is_null($separator)){ $separator = empty( $post->excerpt->rendered ) ? '' : '...'; }

					// Excerpt Generator
					if (count($excerpt)>=$limit) {
						array_pop($excerpt);
						$excerpt = implode(" ",$excerpt).$separator;
					} else { $excerpt = implode(" ",$excerpt); }   
					$excerpt = preg_replace('`\[[^\]]*\]`','',$excerpt);
				} // End Excerpt Limiter
				else $excerpt = '';
				
				$title_size = empty( $a['title_size'] ) ? '' : ' style="font-size:'.$a['title_size'].'px"';
				
				// Check if excerpt exists, if not, show nothing, otherwise ready to add to the featured image title tag
				$title_tag_excerpt = empty( strip_tags($post->excerpt->rendered) ) ? '' : PHP_EOL . PHP_EOL. $excerpt;
				
				// Setup the title tag for links
				$link_title_tag = empty( $post->title->rendered ) ? '' : $post->title->rendered . $title_tag_excerpt;
				
				// Setup starting tag of the post link
				$post_link = '<a href="' . $post->link . '" title="'.$link_title_tag.'">';
				
				// Check if "Post Title" is set to "True"
				if( $a['title'] == 'true') {
				$post_title = $post_link . '<h3 class="mrp-post-title"' . $title_size . '>'. $post->title->rendered . '</h3></a>';
				} else $post_title = '';
				
				// Output the post loop
        		echo $mrpcolumns . $post_link . $post_thumbnail . '</a>' . $post_title . '<span class="mrp-excerpt">' . $excerpt . '</span>' . $humandate . "</div>\n";
				}
    		echo "</div>\n";
			}
		 echo "</div>\n";
		 $output = ob_get_contents();
		}
	 ob_end_clean();
	 return $output;
 }
add_shortcode('restpost', 'MRP_Rest_Post_Shortcode');