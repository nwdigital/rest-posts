=== RESTposts ===
Contributors: mathewemoore
Tags: REST, post, posts, embed, embeds, rest embed, rest embeds, rest post embed, rest post embeds, rest posts embed, rest posts embeds, rest posts, multisite posts, network posts, rest api, post shortcode, posts shortcode, rest posts shortcode, rest post shortcode
Donate link: https://www.restposts.com/donate
Requires at least: 4.6
Tested up to: 4.8.2
Requires PHP: 5.6
Stable tag: 1.1.0
License: GPLv2 or later

Embed posts from your site or others into posts and pages using WP REST API. Great for multisite networks. Sidebar widget included.

== Description ==

RESTposts is the ultimate FREE tool to display and embed posts from WordPress websites. You can use the built-in shortcode generator tool to create the perfect shortcode to display the posts any way you like. Select how many posts you want to display and set your options. Choose from 1, 2, 3 and 4 column display modes with the ability to show or hide the post dates, featured images, excerpts and  more! RESTposts is great for websites that want to show posts from other subsites within their multisite network. Just copy and paste the url of the WordPress website you want to display posts from and go!

**Features of RESTposts include, but are not limited to:**

* Easy shortcode generator to setup your posts to display.
* 1, 2, 3 and 4 column display modes.
* Sidebar Widget.
* Customize the post display to your liking.
* Choose how many posts to display.
* Option to activate shortcodes in the widget sidebar.
* Button to one-click copy the shortcode from the generator.
* Button to reset shortcode generator to defaults.


== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the `rest-posts` directory to your `/wp-content/plugins/` directory
2. Activate the plugin through the \'Plugins\' menu in WordPress
3. Visit the \'RESTposts\' menu item in your admin sidebar

== Frequently Asked Questions ==

[Our documentation can be found here.](http://www.restposts.com/faq/)

== Screenshots ==

1. 2 Column Layout with Shortcode
2. 3 Column Layout with Shortcode
3. 4 Column Layout with Shortcode
4. Dashboard: Shortcode Generator with live shortcodes creator and updater below.
5. REST API Response Test: Test urls to see if they have posts available via REST.

== Changelog ==

= 1.1.0 =

* Simplified Shortcode Generator by adding some defaults
* Added new shortcode [rest_post] to enable the caching mechanism and make publishing and updating shortcodes easier.
* Added transients for 12 Hour caching of JSON repsonse for the new shortcode [rest_post]
* Added post_type option to the shortcode generator
* Added post_type option to sidebar widget
* Updating shortcodes now clears transients cache
* Saved some space in the shortcode generator by modifying some styles and removing some options
* Advanced options are now available in already published shortcodes edit section
* Removed Copy button and associated functions to improve performance
* Removed REST API Test from settings
* Removed "Title Font Size" option (modify using css if needed)
* Fully Revamped the shortcode generator
* Incorporated Javascript based Toggle boxes for editing/updating shortcodes
* CSS Styling Tweaks (Frontend and Backend)
* Added the following options to the edit boxes
** Show/Hide Post Excerpts
** Show/Hide Post Titles
** Show/Hide Post Dates
** Show/Hide Post Featured Images
* Compatible with WordPress 4.8.2

= 1.0.8 =

* Added Requires PHP version 5.6
* Fixed fontawesome issues in backend
* Added "post_type" option to shortcode
* Compatible with WordPress 4.8.1

= 1.0.2 =

* Added Changelog

= 1.0.1 =

* Fixed missing "Enable Shortcodes in Widgets" option
* Added tooltips to Shortcode Creator

= 1.0 =

* Initial Release
