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

add_action("admin_menu", "add_plugin_menu_item");
// Buffer content so wp_redirect doesn't trigger headers already sent error
function app_output_buffer() {
	ob_start();
} // restposts_output_buffer
add_action('init', 'app_output_buffer');

function add_plugin_menu_item()
{
	add_menu_page("RESTposts Settings", "REST Posts", "administrator", "restpost-settings", "plugin_options_page", 'dashicons-media-code', 50);
}

// Create a section and add the submit button
function plugin_options_page()	{
	if( isset( $_GET[ 'tab' ] ) ) {
		$restpost_active_tab = $_GET[ 'tab' ];
	} else {
		$restpost_active_tab = 'shortcodes';
	} ?>
    <div class="wrap">
    	<h1>RESTposts Settings</h1>
		<h2 class="nav-tab-wrapper">
		<a href="?page=restpost-settings&tab=shortcodes" class="nav-tab <?php echo $restpost_active_tab == 'shortcodes' ? 'nav-tab-active' : ''; ?>">Shortcodes</a>
		<a href="?page=restpost-settings&tab=rest-test" class="nav-tab <?php echo $restpost_active_tab == 'rest-test' ? 'nav-tab-active' : ''; ?>">REST API Test</a>
		<a href="?page=restpost-settings&tab=support" class="nav-tab <?php echo $restpost_active_tab == 'support' ? 'nav-tab-active' : ''; ?>">Info & Help</a></h2>
		<form method="post" action="options.php" enctype="multipart/form-data">
			<?php if( $restpost_active_tab == 'shortcodes' )
				{
				settings_fields("section-2");
				do_settings_sections("theme-options-2");
				}
				else if( $restpost_active_tab == 'support' )
				{
				settings_fields("section-3");
				do_settings_sections("theme-options-3");
				}
				else if( $restpost_active_tab == 'rest-test' )
				{
				settings_fields("section");
				do_settings_sections("theme-options");
				submit_button();
				}?>
		</form>
		</div>
<?php // Section 2 Page Contents
if( $restpost_active_tab == 'shortcodes' )	{
// My Shortcodes Tab Extra Functions
// Delete post function
function wp_delete_post_link($link = 'Delete', $before = '', $after = '')	{
global $post;
if ( $post->post_type == 'mrp_restposts' ) {
if ( !current_user_can( 'edit_page', $post->ID ) )
return;
} else {
if ( !current_user_can( 'edit_post', $post->ID ) )
return;
}
		$link = "<a class='button rp-delete' href='" . wp_nonce_url( get_bloginfo('url') . "/wp-admin/post.php?action=delete&amp;post=" . $post->ID, 'delete-post_' . $post->ID) . "'>".$link."</a>";
		echo $before . $link . $after;
	}
	
class RestPost_Create_Update_Feature {
	private $title_update_box;

// Copy Shortcode Function
function rp_copy_shortcode($link = 'Copy', $before = '', $after = '') {
	$alert_title = get_the_title();
	$my_alert = "'$alert_title - The shortcode was successfully copied.'";
	$rp_button_id = "1";
	$rp_button_id .= get_the_ID();
	$rp_button_onclick = ' onclick="postshortcode_copyToClipboard('.$rp_button_id.'); alert('.$my_alert.');"';
	$link = "<input type='submit' name='copy' id='copy' value='Copy' class='button'".$rp_button_onclick.">";
	echo $before . $link . $after;
	}

// Query RESTPOSTS custom post type
public function restposts_shortcode_query() {
	// the query
	$restposts_args = array( 'post_type' => 'mrp_restposts' );
	$the_query = new WP_Query( $restposts_args );
	if ( $the_query->have_posts() ) : ?>
	<!-- pagination here -->
	<!-- the loop -->
		<div id="rp-query" style="margin-top:25px;">
		<h2>My Shortcodes</h2>
		<table class="restposts-shortcode-table">
		<tr><th>Shortcode Title</th><th>Description</th><th colspan="7">Shortcode</th></tr>
		<?php while ( $the_query->have_posts() ) : $the_query->the_post();
			// Set post meta variables
			$this->rp_wurl = get_post_meta( get_the_ID(),'restpost_website_url', true);
			$this->rp_nmpt = get_post_meta( get_the_ID(),'restpost_numb_posts', true);
			$this->rp_nmcl = get_post_meta( get_the_ID(),'restpost_numb_columns', true);
			$this->rp_exlt = get_post_meta( get_the_ID(),'restpost_excpt_limit', true);
			$this->rp_tlft = get_post_meta( get_the_ID(),'restpost_ttl_font_sz', true);
			$this->rp_offset = get_post_meta( get_the_ID(), 'restpost_offset', true);
			// Create shortcode variables
			$rp_wurl_enc = ' url="'.$this->rp_wurl.'"';
			$rp_nmpt_enc = ' count="'.$this->rp_nmpt.'"';
			$rp_nmcl_enc = ' columns="'.$this->rp_nmcl.'"';
			$rp_exlt_enc = (!empty($this->rp_exlt) ? ' excerpt_length="'.$this->rp_exlt.'"' : '' );
			$rp_tlft_enc = (!empty($this->rp_tlft) ? ' title_size="'.$this->rp_tlft.'"' : '' );
			$rp_offset_enc = (!empty($this->rp_offset) ? ' offset="'.$this->rp_offset.'"' : '' );
			// Generate the shortcode
			$rp_new_shortcode = '<code id="1'.get_the_ID().'">[restpost'.$rp_wurl_enc.$rp_nmpt_enc.$rp_nmcl_enc.$rp_exlt_enc.$rp_tlft_enc.$rp_offset_enc.']</code>'; ?>		
			<tr id="<?php echo get_the_ID();?>"><?php echo $this->restpost_update_post(); ?></tr>
			<tr class="rp-rowstart">
			<td colspan="7"><?php echo $rp_new_shortcode; ?></td>
			<td><?php self::rp_copy_shortcode('Copy'); ?></td>
			<td><?php wp_delete_post_link('Delete'); ?><br/></td>
			</tr>
		<?php endwhile; ?>
		</table></div>
		<!-- end of the loop -->
		<!-- pagination here -->
		<?php wp_reset_postdata();
		else : ?>
		<p><?php _e( 'Sorry, no shortcodes found.' ); ?></p>
		<?php endif;
	}

// START CUSTOM POST UPDATE FUNCTION
public function restpost_update_post() {
	
		$submit_id = 'submit-'.get_the_ID();
		$update_id = 'title-'.get_the_ID();
		if( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST[$submit_id] )) { //check that our form was submitted
		$update_id = 'title-'.get_the_ID();
		$update_title = $_POST[$update_id]; //set our title
		
		// Check if items are empty or not
		$rp_up_website_url = (empty($_POST['restpost_website_url']) ? site_url() : $_POST['restpost_website_url'] );
		$rp_up_description = (empty($_POST['restpost_description']) ? '' : $_POST['restpost_description'] );
		$restpost_numb_posts = (empty($_POST['restpost_numb_posts']) ? '6' : $_POST['restpost_numb_posts'] );
		$restpost_numb_columns = (empty($_POST['restpost_numb_columns']) ? '1' : $_POST['restpost_numb_columns'] );
		$restpost_excpt_limit = (empty($_POST['restpost_excpt_limit']) ? '' : $_POST['restpost_excpt_limit'] );
		$restpost_ttl_font_sz = (empty($_POST['restpost_ttl_font_sz']) ? '' : $_POST['restpost_ttl_font_sz'] );
		$restpost_offset = (empty($_POST['restpost_offset']) ? '' : $_POST['restpost_offset'] );
			
		$update_rest_post = array( //our wp_insert_post args
			'ID'			=> get_the_ID(),
			'post_title'	=> wp_strip_all_tags($update_title),
			'post_content'	=> $rp_up_description,
			'post_type' 	=> 'mrp_restposts'
			);			
		// Insert the post and custom meta into the database and return the new post ID
		$update_rest_post_id = wp_update_post( $update_rest_post ); //send our post, save the resulting ID
		//add custom meta data, after the post is inserted
		update_post_meta($update_rest_post_id, 'restpost_website_url', $rp_up_website_url);
		update_post_meta($update_rest_post_id, 'restpost_numb_posts', $restpost_numb_posts);
		update_post_meta($update_rest_post_id, 'restpost_numb_columns', $restpost_numb_columns);
		update_post_meta($update_rest_post_id, 'restpost_excpt_limit', $restpost_excpt_limit);
		update_post_meta($update_rest_post_id, 'restpost_ttl_font_sz', $restpost_ttl_font_sz);
		update_post_meta($update_rest_post_id, 'restpost_offset', $restpost_offset);
		echo wp_redirect(admin_url('admin.php?page=restpost-settings&tab=shortcodes'));
	} else { ?>
	<div id="update_postbox">
	<form class="update-form" id="update_restpost_post" name="update_restpost_post" method="post">
	<td>Title:<br/><input type="text" id="title-<?php echo get_the_ID(); ?>" value="<?php the_title(); ?>" name="title-<?php echo get_the_ID(); ?>" required /></td>
	<td>Description:<br/><input id="restpost_description" name="restpost_description" value="<?php echo get_the_content();?>"/></td>
	<td>Website:<br/><input type="url" id="restpost_website_url" name="restpost_website_url" value="<?php echo $this->rp_wurl; ?>" required /></td>
	<td>Posts:<br/><input type="number" id="restpost_numb_posts" name="restpost_numb_posts" class="tiny-text" value="<?php echo $this->rp_nmpt; ?>" required /></td>
	<td>Columns:<br/><input type="number" id="restpost_numb_columns" name="restpost_numb_columns" class="tiny-text" value="<?php echo $this->rp_nmcl;?>" required /></td>
	<td>Excerpt Limit:<br/><input type="number" id="restpost_excpt_limit" name="restpost_excpt_limit" class="tiny-text" value="<?php echo $this->rp_exlt;?>"/></td>
	<td>Title Size:<br/><input type="number" id="restpost_ttl_font_sz" name="restpost_ttl_font_sz" class="tiny-text" value="<?php echo $this->rp_tlft;?>"/></td>
	<td>Offset:<br/><input type="number" id="restpost_offset" name="restpost_offset" class="tiny-text" value="<?php echo $this->rp_offset;?>"/></td>
	<td><input type="submit" value="Update" id="update_restpost_shortcode" name="update_restpost_shortcode" class="update-button button" /><input type="hidden" name="submit-<?php echo get_the_ID(); ?>" value="post" /></td>
	<?php wp_nonce_field( 'update_restpost_post' ); ?>
	</form>
	</div>
<?php	}	} // END CUSTOM POST UPDATE FUNCTION & FORM

	private $rp_wurl;
	private $rp_nmpt;
	private $rp_nmcl;
	private $rp_exlt;
	private $rp_tlft;
	private $rp_offset;

// START CUSTOM POST CREATION FUNCTION
function restpost_create_new_post() {
		global $wp;
		
		if( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['insert_post'] )) { //check that our form was submitted
		$title = $_POST['shortcode_title']; //set our title
		// Check if items are empty or not
		$restpost_description = (empty($_POST['restpost_description']) ? '' : $_POST['restpost_description'] );
		$restpost_new_website_url = (empty($_POST['restpost_website_url']) ? site_url() : $_POST['restpost_website_url'] );
		$restpost_numb_posts = (empty($_POST['restpost_numb_posts']) ? '6' : $_POST['restpost_numb_posts'] );
		$restpost_numb_posts = (empty($_POST['restpost_numb_posts']) ? '6' : $_POST['restpost_numb_posts'] );
		$restpost_numb_columns = (empty($_POST['restpost_numb_columns']) ? '1' : $_POST['restpost_numb_columns'] );
		$restpost_excpt_limit = (empty($_POST['restpost_excpt_limit']) ? '' : $_POST['restpost_excpt_limit'] );
		$restpost_ttl_font_sz = (empty($_POST['restpost_ttl_font_sz']) ? '' : $_POST['restpost_ttl_font_sz'] );
		$restpost_offset = (empty($_POST['restpost_offset']) ? '' : $_POST['restpost_offset'] );
			
		$rest_post = array( //our wp_insert_post args
			'post_title'	=> wp_strip_all_tags($title),
			'post_content'	=> $restpost_description,
			'post_status'	=> 'publish',
			'post_type' 	=> 'mrp_restposts'
			);			
		// Insert the post and custom meta into the database and return the new post ID
		$rest_post_id = wp_insert_post( $rest_post ); //send our post, save the resulting ID			
		//add custom meta data, after the post is inserted
		add_post_meta($rest_post_id, 'restpost_website_url', $restpost_new_website_url);
		add_post_meta($rest_post_id, 'restpost_numb_posts', $restpost_numb_posts);
		add_post_meta($rest_post_id, 'restpost_numb_columns', $restpost_numb_columns);
		add_post_meta($rest_post_id, 'restpost_excpt_limit', $restpost_excpt_limit);
		add_post_meta($rest_post_id, 'restpost_ttl_font_sz', $restpost_ttl_font_sz);
		add_post_meta($rest_post_id, 'restpost_offset', $restpost_offset);
		echo wp_redirect(admin_url('admin.php?page=restpost-settings&tab=shortcodes')); 
	} else { ?>
	<div id="postbox">
	<h2>Shortcode Creator</h2>
	<form id="new_restpost_post" name="new_restpost_post" method="post" action="">
		<table class="restposts-shortcode-table">
	<tr><th>Title</th><th>Description</th><th>Website</th><th>#Posts</th><th>#Columns</th><th>Excerpt Limit</th><th>Title Size</th><th colspan="2">Offset</th></tr>
			<td><input type="text" id="shortcode_title" value="" name="shortcode_title" required /></td>
			<td><input type="text" id="restpost_description" name="restpost_description" cols="80" rows="20"/></td>
			<td><input type="url" id="restpost_website_url" value="" name="restpost_website_url" required /></td>
			<td><input type="number" class="tiny-text" id="restpost_numb_posts" value="" name="restpost_numb_posts" required /></td>
			<td><input type="number" class="tiny-text" id="restpost_numb_columns" value="" name="restpost_numb_columns" required /></td>
			<td><input type="number" class="tiny-text" id="restpost_excpt_limit" value="" name="restpost_excpt_limit" /></td>
			<td><input type="number" class="tiny-text" id="restpost_ttl_font_sz" value="" name="restpost_ttl_font_sz" /></td>
			<td><input type="number" class="tiny-text" id="restpost_offset" value="" name="restpost_offset" /></td>
			<td><input type="submit" value="Publish" tabindex="5" id="publish_restpost_shortcode" name="publish_restpost_shortcode" class="button button-primary" /></td>
			<input type="hidden" name="insert_post" value="post" />
		<?php wp_nonce_field( 'new_restpost_post' ); ?>
		</table>
	</form>
	</div>
<?php	}	} // END CUSTOM POST CREATION FUNCTION & FORM
}
	$create_update_class = new RestPost_Create_Update_Feature;
	echo $create_update_class->restpost_create_new_post();
	echo $create_update_class->restposts_shortcode_query();

}
// HTML for Section 3 / Tab 3
if( $restpost_active_tab == 'support' ) { ?>
<h3>Support Future Plugin Development</h3>
<p>Did you find this plugin useful? If so, show your support and donate today!</p>
<p>Your donations support the future development and feature enhancements for this plugin. Donations of any amount will be greatly appreciated.</p>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="J7C66Y92MG7TC">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
<h2>Publishing New Shortcodes</h2>
<ol>
<li><strong>Shortcode Title</strong>: Allows you to label shortcodes for future reference. This is not shown on the front of the website.</li>
<li><strong>Description</strong>: Allows you to add a description to your shortcode. Useful for knowing placement locations, etc.</li>
</ol><br/>
<h2>Shortcode Options - useful for creating shortcodes manually. <code>[restpost]</code></h2>
<ol>
<li><strong>url</strong> - This is the website address you want to display posts from.  <strong>Example</strong>: <code>[url="<?php echo site_url();?>"]</code></li>
<li><strong>columns</strong> - The number of columns to display posts in (1, 2, 3 or 4).  <strong>Example</strong>: <code>[columns="3"]</code></li>
<li><strong>date</strong> - Shows the post date by default. Specify false to hide.  <strong>Example</strong>: unspecified or <code>[date="true"]</code> or <code>[date="false"]</code></li>
<li><strong>title (default="true")</strong> - Shows the post title by default. Specify false to hide.  <strong>Example</strong>: unspecified or <code>[title="true"]</code> or <code>[title="false"]</code></li>
<li><strong>img (default="true")</strong> - Shows the post image by default. Specify false to hide.  <strong>Example</strong>: unspecified or <code>[img="true"]</code> or <code>[img="false"]</code></li>
<li><strong>title_size (optional)</strong> - Specify an optional font size for the post title in pixels(px).  <strong>Example</strong>: <code>[title_size="18"]</code></li>
<li><strong>excerpt (default="true")</strong> - Shows the post excerpt by default. Specify false to hide.  <strong>Example</strong>: unspecified or <code>[excerpt="true"]</code> or <code>[excerpt="false"]</code></li>
<li><strong>excerpt_length (optional)</strong> - Specify an optional excerpt word length.  <strong>Example</strong>: <code>[excerpt_length="10"]</code></li>
<li><strong>offset (optional)</strong> - Specify an optional post offset. This will skip the recent post by number you specify.  <strong>Example</strong>: <code>[offset="2"]</code></li>
</ol><br/>
<h3>Complete Shortcode Example With All Options Set</h3>
<ul>
	<li>In this example, we have several items listed as ="true". We don't necessarily need to include these since they will be displayed by default.</li>
	<li><code>[restpost url="<?php echo site_url();?>" count="8" columns="4" date="true" title="true" img="true" title_size="18" excerpt="true" excerpt_length="1" offset="2"]</code></li>
</ul>
<ul>
	<li>The example below will display posts exactly the same as the shortcode above even though we left out the ="true" statements.</li>
	<li><code>[restpost url="http://www.restposts.com" count="8" columns="4" excerpt_length="1" title_size="18" offset="2"]</code></li>
</ul>
<br/>

<p><a href="http://www.restposts.com/contact">Contact Us</a> | <a href="http://www.restposts.com">Visit RESTposts.com</a></p>
<?php }

}

function restpost_link_checker_element()
	{
		?>
			<input type="url" name="restpost_link_checker" class="regular-text" id="restpost_link_checker" value="<?php echo get_option('restpost_link_checker'); ?>" required />
<?php $valid_url = wp_remote_get( get_option('restpost_link_checker') . '/wp-json/wp/v2/posts?per_page=1' );
	$posts = json_decode( wp_remote_retrieve_body( $valid_url ) );

	if( empty( $posts ) ) {
		echo '<span style="margin-right:10px;color:red;"><i class="fa fa-times-circle fa-lg" aria-hidden="true"></i></span>';
	}
	else {
		echo '<span style="margin-right:10px;color:green;"><i class="fa fa-check-circle fa-lg" aria-hidden="true"></i></span>';
	}
	}

// Sections & Tabs
function restpost_plugin_panel_fields()
	{
		// Link Checker Section
		add_settings_section("section", "Use the form below to test the url for valid WP REST API response", null, "theme-options");
	
		// Settings Fields
		add_settings_field("restpost_link_checker", "REST API Check", "restpost_link_checker_element", "theme-options", "section");
		
		// Register Settings
		register_setting("section", "restpost_link_checker");
	}

add_action("admin_init", "restpost_plugin_panel_fields");
