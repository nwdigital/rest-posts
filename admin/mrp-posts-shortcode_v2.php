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
function MRP_Rest_Post_Shortcode_v2( $atts ) {

		$a = shortcode_atts( array(
			'id'					=> '',
			'url' 				=> site_url(),
			'img' 				=> 'true',
    ), $atts );

    // Define a variable for the shortcode id
    $mrpid =$a['id'];
    // Setup variable for the post meta
    $restpost_transient =	'restpost_transient_id_'.$mrpid;
    // Setup variable for the post meta
    $rp_post_type = get_post_meta( $mrpid,'restpost_post_type', true);
    $rp_wurl = get_post_meta( $mrpid,'restpost_website_url', true);
    $rp_count = get_post_meta( $mrpid,'restpost_numb_posts', true);
    $rp_nmcl = get_post_meta( $mrpid,'restpost_numb_columns', true);
    $rp_exlt = get_post_meta( $mrpid,'restpost_excpt_limit', true);
		$rp_show_excpt = get_post_meta( $mrpid,'restpost_show_excpt', true);
		$rp_show_title = get_post_meta( $mrpid,'restpost_show_title', true);
		$rp_show_date = get_post_meta( $mrpid,'restpost_show_date', true);
		$rp_show_img = get_post_meta( $mrpid,'restpost_show_img', true);
    $rp_tlft = get_post_meta( $mrpid,'restpost_ttl_font_sz', true);
    $rp_offset = get_post_meta( $mrpid, 'restpost_offset', true);

	// Get any existing copy of our transient data
	if ( false === ( $restpost_cached_response = get_transient( $restpost_transient ) ) ) {
	    // It wasn't there, so regenerate the data and save the transient
			// Start REST API
			if (empty($rp_offset)) {
		 		$restpost_cached_response = wp_remote_get( $rp_wurl . '/wp-json/wp/v2/'.$rp_post_type.'/?per_page=' . $rp_count );
		 	}
		 	else {
		 		$restpost_cached_response = wp_remote_get( $rp_wurl . '/wp-json/wp/v2/'.$rp_post_type.'/?per_page=' . $rp_count . '&offset=' . $rp_offset );
		 	}
		 	if( is_wp_error( $restpost_cached_response ) ) {
		 		return;
		 	}
	     set_transient( $restpost_transient, $restpost_cached_response, 12 * HOUR_IN_SECONDS );
	}

	$posts = json_decode( wp_remote_retrieve_body( $restpost_cached_response ) );

	if( empty( $posts ) ) {
		return;
	}
	ob_start ();
	 if( !empty( $posts ) ) {
		 // Start the main container class
		 echo "<div class='mrp-container'>\n";
		 foreach (array_chunk($posts, $rp_nmcl) as $post) {

			echo "<div class='mrp-row'>\n";
			foreach ( $post as $post ) {

				// Create the single post wrapper class
				$mrpcolumns = ( empty( $rp_nmcl ) ) ? '' : '<div class="mrp-'.$rp_nmcl.'-column">';

				if( $rp_show_date == 'true' ) {
				// Check if Date is set to 'True'
				$jsondate = new DateTime( $post->date );
				$humandate = '<span class="mrp-post-date">'.$jsondate->format("F d, Y").'</span>';
				} else $humandate = '';

				// Check if "Post Image" is set to "True"
				if( $rp_show_img == 'true' ) {
				$post_thumbnail = '<img class="mrp-img" src="'. $post->swp_thumbnail .'"/>';
				} else $post_thumbnail = '';

				// Start Excerpt Limiter
				if( $rp_show_excpt == 'true' ) {

					$the_excerpt = strip_tags($post->excerpt->rendered);
					$limit = null; $separator = null;
					// Set standard words limit
					if (is_null($limit)){
						$excerpt = explode(' ', $the_excerpt, $rp_exlt+1);
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

				$title_size = empty( $rp_tlft ) ? '' : ' style="font-size:'.$rp_tlft.'px"';

				// Check if excerpt exists, if not, show nothing, otherwise ready to add to the featured image title tag
				$title_tag_excerpt = empty( strip_tags($post->excerpt->rendered) ) ? '' : PHP_EOL . PHP_EOL. $excerpt;

				// Setup the title tag for links
				$link_title_tag = empty( $post->title->rendered ) ? '' : $post->title->rendered . $title_tag_excerpt;

				// Setup starting tag of the post link
				$post_link = '<a href="' . $post->link . '" title="'.$link_title_tag.'">';

				// Check if "Post Title" is set to "True"
				if( $rp_show_title == 'true') {
				$post_title = $post_link . '<h2 class="mrp-post-title"' . $title_size . '>'. $post->title->rendered . '</h2></a>';
				} else $post_title = '';

				// Output the post loop
        		echo $mrpcolumns . $post_title . $humandate . $post_link . $post_thumbnail . '</a><span class="mrp-excerpt">' . $excerpt . '</span>' . "</div>\n";
				}
    		echo "</div>\n";
			}
		 echo "</div>\n";
		 $output = ob_get_contents();
		}
	 ob_end_clean();
	 return $output;
 }
add_shortcode('rest_post', 'MRP_Rest_Post_Shortcode_v2');
