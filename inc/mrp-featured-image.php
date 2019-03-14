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

// Add Featured Image URL to Rest API Json Response
add_action( 'rest_api_init', 'swp_insert_thumbnail_url' );
function swp_insert_thumbnail_url() {
    register_rest_field( 'post',
        'swp_thumbnail',
        array(
            'get_callback'    => 'swp_get_thumbnail_url',
            'update_callback' => null,
            'schema'          => null,
        )
    );
}

function swp_get_thumbnail_url($post){
	if(has_post_thumbnail($post['id'])){
		$imgArray = wp_get_attachment_image_src( get_post_thumbnail_id( $post['id'] ), 'full' );
		$imgURL = $imgArray[0];
		return $imgURL;
	}else{
		return false;	
	}
}