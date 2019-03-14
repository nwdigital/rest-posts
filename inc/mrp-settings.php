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
		<a href="?page=restpost-settings&tab=support" class="nav-tab <?php echo $restpost_active_tab == 'support' ? 'nav-tab-active' : ''; ?>">Info & Help</a></h2>
		<form id="rpsts_form" method="post" action="options.php" enctype="multipart/form-data">
			<?php if( $restpost_active_tab == 'shortcodes' )
				{
				settings_fields("section-2");
				do_settings_sections("plugin-options-2");
				}
				else if( $restpost_active_tab == 'support' )
				{
				settings_fields("section-3");
				do_settings_sections("plugin-options-3");
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
function RestPosts_Shortcode_Generator() {

	}

// Query RESTPOSTS custom post type
public function restposts_shortcode_query() {
	// the query
	$restposts_args = array( 'post_type' => 'mrp_restposts', 'posts_per_page'	=> '-1' );
	$the_query = new WP_Query( $restposts_args );
	if ( $the_query->have_posts() ) : ?>
	<!-- pagination here -->
	<!-- the loop -->
		<div id="rp-query" style="margin-top:25px;">

		<table class="restposts-shortcode-table">
			<tr>
				<th colspan="10">
					<div class="rp_shortcode_heading">
						<div><span>TITLE</span></div>
						<div><span>SHORTCODE</span></div>
						<div><span>DATE PUBLISHED</span></div>
						<div><span>EDIT</span></div>
					</div>
				</th>
			</tr>
		<?php while ( $the_query->have_posts() ) : $the_query->the_post();
			// Set post meta variables
			$this->rp_post_type = get_post_meta( get_the_ID(),'restpost_post_type', true);
			$this->rp_wurl = get_post_meta( get_the_ID(),'restpost_website_url', true);
			$this->rp_nmpt = get_post_meta( get_the_ID(),'restpost_numb_posts', true);
			$this->rp_nmcl = get_post_meta( get_the_ID(),'restpost_numb_columns', true);
			$this->rp_exlt = get_post_meta( get_the_ID(),'restpost_excpt_limit', true);
			$this->rp_show_excpt = get_post_meta( get_the_ID(),'restpost_show_excpt', true);
			$this->rp_show_title = get_post_meta( get_the_ID(),'restpost_show_title', true);
			$this->rp_show_date = get_post_meta( get_the_ID(),'restpost_show_date', true);
			$this->rp_show_img = get_post_meta( get_the_ID(),'restpost_show_img', true);
			$this->rp_offset = get_post_meta( get_the_ID(), 'restpost_offset', true);
			// Create shortcode variables
			// $rp_post_id = ' id="'.get_the_ID().'"';
			$rp_post_id = get_the_ID();
			$rp_wurl_enc = ' url="'.$this->rp_wurl.'"';
			$rp_post_type_enc = ' post_type="'.$this->rp_post_type.'"';

			$rp_nmpt_enc = ' count="'.$this->rp_nmpt.'"';
			$rp_nmcl_enc = ' columns="'.$this->rp_nmcl.'"';
			$rp_exlt_enc = (!empty($this->rp_exlt) ? ' excerpt_length="'.$this->rp_exlt.'"' : '' );
			$rp_show_excpt_enc = (!empty($this->rp_show_excpt) ? ' excerpt="true"' : '' );
			$rp_show_title_enc = (!empty($this->rp_show_title) ? ' title="true"' : '' );
			$rp_show_date_enc = (!empty($this->rp_show_date) ? ' date="true"' : '' );
			$rp_show_img_enc = (!empty($this->rp_show_img) ? ' img="true"' : '' );
			$rp_offset_enc = (!empty($this->rp_offset) ? ' offset="'.$this->rp_offset.'"' : '' );
			// Generate the shortcode
			?>

			<tr class="rp-rowstart">
				<td colspan="10">
					<div class="rp_shortcode_details">
						<div><span><?php echo get_the_title() . (!empty(get_the_content()) ? ' - ' : '' ) . get_the_content(); ?></span></div>
						<div class="shortcode_section">
							<span class="mrp_copy_shortcode" id="1<?php echo $rp_post_id; ?>">[rest_post id=<?php echo $rp_post_id; ?>]</span>
							<div>Copied</div>
						</div>
						<div><span><?php echo get_the_date(); ?></span></div>
						<div><span><button class="button button-primary restpost_edit" data-index="rptoggle_<?php echo $rp_post_id; ?>" href="#">edit</button></span></div>
					</div>
					<div class="toggle_box_restposts" id="rptoggle_<?php echo get_the_ID();?>"><?php echo $this->restpost_update_post(); ?></div>
				</td>
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
		$restpost_post_type = (empty($_POST['restpost_post_type']) ? site_url() : $_POST['restpost_post_type'] );
		$rp_up_website_url = (empty($_POST['restpost_website_url']) ? site_url() : $_POST['restpost_website_url'] );
		$rp_up_description = (empty($_POST['restpost_description']) ? '' : $_POST['restpost_description'] );
		$restpost_numb_posts = (empty($_POST['restpost_numb_posts']) ? '6' : $_POST['restpost_numb_posts'] );
		$restpost_numb_columns = (empty($_POST['restpost_numb_columns']) ? '1' : $_POST['restpost_numb_columns'] );
		$restpost_excpt_limit = (empty($_POST['restpost_excpt_limit']) ? '' : $_POST['restpost_excpt_limit'] );
		$restpost_show_excpt = (empty($_POST['restpost_show_excpt']) ? '' : $_POST['restpost_show_excpt'] );
		$restpost_show_title = (empty($_POST['restpost_show_title']) ? '' : $_POST['restpost_show_title'] );
		$restpost_show_date = (empty($_POST['restpost_show_date']) ? '' : $_POST['restpost_show_date'] );
		$restpost_show_img = (empty($_POST['restpost_show_img']) ? '' : $_POST['restpost_show_img'] );
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
		update_post_meta($update_rest_post_id, 'restpost_post_type', $restpost_post_type);
		update_post_meta($update_rest_post_id, 'restpost_website_url', $rp_up_website_url);
		update_post_meta($update_rest_post_id, 'restpost_numb_posts', $restpost_numb_posts);
		update_post_meta($update_rest_post_id, 'restpost_numb_columns', $restpost_numb_columns);
		update_post_meta($update_rest_post_id, 'restpost_excpt_limit', $restpost_excpt_limit);
		update_post_meta($update_rest_post_id, 'restpost_show_excpt', $restpost_show_excpt);
		update_post_meta($update_rest_post_id, 'restpost_show_title', $restpost_show_title);
		update_post_meta($update_rest_post_id, 'restpost_show_date', $restpost_show_date);
		update_post_meta($update_rest_post_id, 'restpost_show_img', $restpost_show_img);
		update_post_meta($update_rest_post_id, 'restpost_ttl_font_sz', $restpost_ttl_font_sz);
		update_post_meta($update_rest_post_id, 'restpost_offset', $restpost_offset);
		echo wp_redirect(admin_url('admin.php?page=restpost-settings&tab=shortcodes'));
	} else { ?>
	<div id="update_postbox">
	<form class="update-form" id="update_restpost_post" name="update_restpost_post" method="post">
		<div class="mrp_edit_table">
			<div class="mrp_edit_col_1">
				<div class="mrp_edit_item">Title&nbsp;&nbsp;
					<input type="text" id="title-<?php echo get_the_ID(); ?>" value="<?php the_title(); ?>" name="title-<?php echo get_the_ID(); ?>" required /></div>
				<div class="mrp_edit_item">Description&nbsp;&nbsp;
					<input type="text" id="restpost_description" name="restpost_description" value="<?php echo get_the_content();?>"/></div>
				<div class="mrp_edit_item">Website&nbsp;&nbsp;
					<input type="url" id="restpost_website_url" name="restpost_website_url" value="<?php echo $this->rp_wurl; ?>" required /></div>
				<div class="mrp_edit_item">Post Type&nbsp;&nbsp;
					<input type="text" id="restpost_post_type" name="restpost_post_type" value="<?php echo $this->rp_post_type;?>"/></div>
			</div>
			<div class="mrp_edit_col_2">
				<div class="mrp_edit_item">Number of Posts to Display&nbsp;&nbsp;
					<input type="number" id="restpost_numb_posts" name="restpost_numb_posts" class="tiny-text" min="1" max="20" value="<?php echo $this->rp_nmpt; ?>" required />
				</div>
				<div class="mrp_edit_item">Number of Columns&nbsp;&nbsp;
					<input type="number" id="restpost_numb_columns" name="restpost_numb_columns" class="tiny-text" min="1" max="4" value="<?php echo $this->rp_nmcl;?>" required />
				</div>
				<div class="mrp_edit_item">Skip Some Recent Posts&nbsp;&nbsp;
					<input type="number" id="restpost_offset" name="restpost_offset" class="tiny-text" min="0" value="<?php echo $this->rp_offset;?>"/>
				</div>
				<div class="mrp_edit_item">Limit Excerpt Character Length&nbsp;&nbsp;
					<input type="number" id="restpost_excpt_limit" name="restpost_excpt_limit" min="10" class="tiny-text" value="<?php echo $this->rp_exlt;?>"/>
				</div>
			</div>
			<div class="mrp_edit_col_3">
				<div class="mrp_edit_item">
					<input type="checkbox" id="restpost_show_excpt" name="restpost_show_excpt" <?php checked( !empty($this->rp_show_excpt), true ); ?> value='true'>Show Post Excerpts
				</div>
				<div class="mrp_edit_item">
					<input type="checkbox" id="restpost_show_title" name="restpost_show_title" <?php checked( !empty($this->rp_show_title), true ); ?> value='true'>Show Post Titles
				</div>
				<div class="mrp_edit_item">
					<input type="checkbox" id="restpost_show_date" name="restpost_show_date" <?php checked( !empty($this->rp_show_date), true ); ?> value='true'>Show Post Dates
				</div>
				<div class="mrp_edit_item">
					<input type="checkbox" id="restpost_show_img" name="restpost_show_img" <?php checked( !empty($this->rp_show_img), true ); ?> value='true'>Show Featured Images
				</div>
			</div>
			<div class="mrp_edit_col_4">
				<div class="mrp_edit_item">
					<input type="submit" value="Update" id="update_restpost_shortcode" name="update_restpost_shortcode" class="update-button button" />
				</div>
				<div class="mrp_edit_item">
					<input type="hidden" name="submit-<?php echo get_the_ID(); ?>" value="post" /><?php wp_delete_post_link('Delete'); ?>
				</div>
			</div>
		</div>
		<p><span style="text-align:right;color:red;">*</span> indicates required field
	<?php wp_nonce_field( 'update_restpost_post' ); ?>
	</form>
	</div>
<?php	}	} // END CUSTOM POST UPDATE FUNCTION & FORM

	private $rp_wurl;
	private $rp_post_type;
	private $rp_nmpt;
	private $rp_nmcl;
	private $rp_exlt;
	private $rp_show_excpt;
	private $rp_show_title;
	private $rp_show_date;
	private $rp_show_img;
	private $rp_offset;

// START CUSTOM POST CREATION FUNCTION
function restpost_create_new_post() {
		global $wp;

		if( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['insert_post'] )) { //check that our form was submitted
		$title = $_POST['shortcode_title']; //set our title
		// Check if items are empty or not
		$restpost_new_post_type = (empty($_POST['restpost_post_type']) ? '' : $_POST['restpost_post_type'] );
		$restpost_new_website_url = (empty($_POST['restpost_website_url']) ? site_url() : $_POST['restpost_website_url'] );
		$restpost_numb_posts = (empty($_POST['restpost_numb_posts']) ? '6' : $_POST['restpost_numb_posts'] );
		$restpost_numb_posts = (empty($_POST['restpost_numb_posts']) ? '6' : $_POST['restpost_numb_posts'] );
		$restpost_numb_columns = (empty($_POST['restpost_numb_columns']) ? '1' : $_POST['restpost_numb_columns'] );
		$restpost_excpt_limit = (empty($_POST['restpost_excpt_limit']) ? '32' : $_POST['restpost_excpt_limit'] );
		$restpost_show_excpt = (empty($_POST['restpost_show_excpt']) ? 'true' : $_POST['restpost_show_excpt'] );
		$restpost_show_title = (empty($_POST['restpost_show_title']) ? 'true' : $_POST['restpost_show_title'] );
		$restpost_show_date = (empty($_POST['restpost_show_date']) ? 'true' : $_POST['restpost_show_date'] );
		$restpost_show_img = (empty($_POST['restpost_show_img']) ? 'true' : $_POST['restpost_show_img'] );
		$restpost_ttl_font_sz = (empty($_POST['restpost_ttl_font_sz']) ? '' : $_POST['restpost_ttl_font_sz'] );
		$restpost_offset = (empty($_POST['restpost_offset']) ? '' : $_POST['restpost_offset'] );

		$rest_post = array( //our wp_insert_post args
			'post_title'	=> wp_strip_all_tags($title),
			'post_content'	=> '',
			'post_status'	=> 'publish',
			'post_type' 	=> 'mrp_restposts'
			);
		// Insert the post and custom meta into the database and return the new post ID
		$rest_post_id = wp_insert_post( $rest_post ); //send our post, save the resulting ID
		//add custom meta data, after the post is inserted
		add_post_meta($rest_post_id, 'restpost_post_type', $restpost_new_post_type);
		add_post_meta($rest_post_id, 'restpost_website_url', $restpost_new_website_url);
		add_post_meta($rest_post_id, 'restpost_numb_posts', $restpost_numb_posts);
		add_post_meta($rest_post_id, 'restpost_numb_columns', $restpost_numb_columns);
		add_post_meta($rest_post_id, 'restpost_excpt_limit', $restpost_excpt_limit);
		add_post_meta($rest_post_id, 'restpost_show_excpt', $restpost_show_excpt);
		add_post_meta($rest_post_id, 'restpost_show_title', $restpost_show_title);
		add_post_meta($rest_post_id, 'restpost_show_date', $restpost_show_date);
		add_post_meta($rest_post_id, 'restpost_show_img', $restpost_show_img);
		add_post_meta($rest_post_id, 'restpost_ttl_font_sz', $restpost_ttl_font_sz);
		add_post_meta($rest_post_id, 'restpost_offset', $restpost_offset);
		echo wp_redirect(admin_url('admin.php?page=restpost-settings&tab=shortcodes'));
	} else {
		/*
		 Start Shortcode Generator Table
		 */
		 ?>
	<div id="postbox">
	<form id="new_restpost_post" name="new_restpost_post" method="post" action="">
		<table class="restposts-shortcode-table">
	<tr>
		<th colspan="8">Shortcode Creator</th>
		</tr>
			<td>Title:<br/>
				<input type="text" id="shortcode_title" value="" name="shortcode_title" required />
			</td>
			<td>
				<span class="rp_tooltip"><span class="rp_tooltiptext">Enter the WordPress website url like:<br/>https://www.restposts.com</span>Website:</span><br/>
				<input type="url" id="restpost_website_url" value="<?php echo site_url(); ?>" name="restpost_website_url" required />
			</td>
			<td>
				<span class="rp_tooltip"><span class="rp_tooltiptext">How many posts would you like to display with this shortcode?</span>Posts:</span><br/>
				<input type="number" class="tiny-text" id="restpost_numb_posts" value="3" name="restpost_numb_posts" required />
			</td>
			<td>
				<span class="rp_tooltip">
					<span class="rp_tooltiptext">How many columns would you like to display the posts in? (1, 2, 3 or 4 columns)
					</span>Columns:</span><br/>
				<input type="number" class="tiny-text" id="restpost_numb_columns" value="1" name="restpost_numb_columns" required />
			</td>
			<td>
				<span class="rp_tooltip"><span class="rp_tooltiptext">Limit the number of words to be displayed from the excerpts.</span>Excerpt Limit:</span><br/>
				<input type="number" class="tiny-text" id="restpost_excpt_limit" value="" name="restpost_excpt_limit" />
			</td>
			<td>
				<span class="rp_tooltip"><span class="rp_tooltiptext">Enter a number here if you want to skip a few posts.</span>Offset:</span><br/>
				<input type="number" class="tiny-text" id="restpost_offset" value="" name="restpost_offset" />
			</td>
			<td>Post Type:<br/>
				<input type="text" id="restpost_post_type" value="posts" name="restpost_post_type" />
			</td>
			<td><br/>
				<input type="submit" value="Publish" tabindex="5" id="publish_restpost_shortcode" name="publish_restpost_shortcode" class="button button-primary" />
			</td>
			<input type="hidden" name="insert_post" value="post" />
		<?php wp_nonce_field( 'new_restpost_post' ); ?>
		</table>
	</form>
	</div>
<?php	}	} // END CUSTOM POST CREATION FUNCTION & FORM aka Shortcode Generator
}
	$create_update_class = new RestPost_Create_Update_Feature;
	echo $create_update_class->restpost_create_new_post();
	echo $create_update_class->restposts_shortcode_query();
}
// HTML for Section 3 / Tab 3
if( $restpost_active_tab == 'support' ) { ?>
<div class="postbox-conatiner" id="restpost_supportab">
<div class="postbox">
<div class="inside">
<h3>Support Future Plugin Development</h3>
<p>Did you find this plugin useful? If so, show your support and donate today!</p>
<p>Your donations support future development and feature enhancements for this plugin. Donations of any amount are accepted and very much appreciated.</p>
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

<h2>Please Visit My Website For Support</h2>
<p><a href="http://www.restposts.com/contact">Contact Us</a> | <a href="http://www.restposts.com">Visit RESTposts.com</a></p>
</div></div></div>
<?php }

}

function enable_widget_shortcodes_element()
	{ ?>
		<div class="rp_tooltip">
		<input type="checkbox" name="enable_widget_shortcodes" class="checkbox" id="enable_widget_shortcodes" <?php checked( get_option('enable_widget_shortcodes')); ?> ) value="1" /><span class="rp_tooltiptext">Check this box if you want to use shortcodes in widget areas.</span>
</div><span style="margin-left:25px;"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></span>
	<?php }

	if (isset($_POST['update_restpost_shortcode'])){
		$delete_posts_ids = get_posts('post_type=mrp_restposts&posts_per_page=-1&fields=ids');
		foreach ($delete_posts_ids as $delete_id ) {
			delete_transient( 'restpost_transient_id_'.$delete_id );
		}
	} else {};

// Sections & Tabs
function restpost_plugin_panel_fields()
	{
		// Link Checker Section
		// add_settings_section("section", "Use the form below to test the url for valid WP REST API response", null, "plugin-options");

		// Enable Widget Shortcodes Section
		add_settings_section("section-2", null, null, "plugin-options-2");

		// Settings Fields
		// add_settings_field("restpost_link_checker", "REST API Check", "restpost_link_checker_element", "plugin-options", "section");

		// Enable Widget Fields
		add_settings_field("enable_widget_shortcodes", "Enable Shortcode Usage in Text Widget Sidebars", "enable_widget_shortcodes_element", "plugin-options-2", "section-2");

		// Register Settings
		// register_setting("section", "restpost_link_checker");
		register_setting("section-2", "enable_widget_shortcodes");
	}

add_action("admin_init", "restpost_plugin_panel_fields");

(!empty(get_option('enable_widget_shortcodes')) ? add_filter('widget_text', 'do_shortcode') : '');
