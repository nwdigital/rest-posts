<?php

if ( class_exists( 'WP_Widget' ) ) {
class RESTposts_widget extends WP_Widget {
	
	public function __construct() {
		$widget_details = array(
			'classname' => 'matts-rest-api-post-widget',
			'description' => 'A REST API widget that pulls posts from a website'
		);

		parent::__construct( 'restposts_widget', 'REST Posts Widget', $widget_details );

	}
	
	// Widget Form
	public function form( $instance ) {
        $title = ( !empty( $instance['title'] ) ) ? $instance['title'] : '';
		$siteurl = ( ! empty( $instance['siteurl'] ) ) ? ( $instance['siteurl'] ) : site_url();
			if ( ! $siteurl )
				$siteurl = site_url();
		$numpost = ( ! empty( $instance['numpost'] ) ) ? absint( $instance['numpost'] ) : 5;
			if ( ! $numpost )
				$numpost = 5;
		$numexcerpt = ( ! empty( $instance['excerpt_length'] ) ) ? absint( $instance['excerpt_length'] ) : 20;
			if ( ! $numexcerpt )
				$numexcerpt = 20;
		$featimg = isset( $instance['featimg'] ) ? (bool) $instance['featimg'] : false;
		$mrp_excerpt = isset( $instance['excerpt'] ) ? (bool) $instance['excerpt'] : false;
		$showtitle = isset( $instance['showtitle'] ) ? (bool) $instance['showtitle'] : false;
		$showdate = isset( $instance['showdate'] ) ? (bool) $instance['showdate'] : false;
		$valid_url = wp_remote_get( ( $siteurl ) . '/wp-json/wp/v2/posts?per_page=1' );
		$posts = json_decode( wp_remote_retrieve_body( $valid_url ) );
		if( empty( $posts ) ) {
		$url_checkmark = '<span style="margin-right:10px;color:red;"><i class="fa fa-times-circle fa-lg" aria-hidden="true"></i></span>';
		$url_checkmark .= "<span>I didn't find a valid WP REST endpoint.</span>";
		}
		else {
		$url_checkmark = '<span style="margin-right:10px;color:green;"><i class="fa fa-check-circle fa-lg" aria-hidden="true"></i></span>';
		$url_checkmark .= '<span>Nice! I found some posts at this url.</span>';
		}
        ?>

        <p><label for="<?php echo $this->get_field_name( 'title' ); ?>">Title: </label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>
        
        <p><label for="<?php echo $this->get_field_name( 'siteurl' ); ?>">Show posts from: </label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'siteurl' ); ?>" name="<?php echo $this->get_field_name( 'siteurl' ); ?>" type="text" value="<?php echo esc_attr( $siteurl ); ?>" /><?php echo $url_checkmark; ?></p>
        
        <p><label for="<?php echo $this->get_field_name( 'numpost' ); ?>">Number of posts to show: </label>
        <input class="tiny-text" id="<?php echo $this->get_field_id( 'numpost' ); ?>" name="<?php echo $this->get_field_name( 'numpost' ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $numpost ); ?>" /></p>
        
        <p><input class="checkbox" type="checkbox"<?php checked( $featimg ); ?> id="<?php echo $this->get_field_id( 'featimg' ); ?>" name="<?php echo $this->get_field_name( 'featimg' ); ?>"  />
        <label for="<?php echo $this->get_field_name( 'featimg' ); ?>">Show Featured Image? <?php echo '<a href="http://www.restposts.com/faq/" target="_blank">Learn More</a>'; ?> </label></p>
           
        <p><input class="checkbox" type="checkbox"<?php checked( $mrp_excerpt ); ?> id="<?php echo $this->get_field_id( 'excerpt' ); ?>" name="<?php echo $this->get_field_name( 'excerpt' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'excerpt' ); ?>"><?php _e( 'Display Excerpt?' ); ?></label></p>
          
        <p><label for="<?php echo $this->get_field_name( 'excerpt_length' ); ?>">Excerpt Word Limit: </label>
        <input class="tiny-text" id="<?php echo $this->get_field_id( 'excerpt_length' ); ?>" name="<?php echo $this->get_field_name( 'excerpt_length' ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $numexcerpt ); ?>" /></p>
            
        <p><input class="checkbox" type="checkbox"<?php checked( $showtitle ); ?> id="<?php echo $this->get_field_id( 'showtitle' ); ?>" name="<?php echo $this->get_field_name( 'showtitle' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'showtitle' ); ?>"><?php _e( 'Display Post Title?' ); ?></label></p>
       
       	<p><input class="checkbox" type="checkbox"<?php checked( $showdate ); ?> id="<?php echo $this->get_field_id( 'showdate' ); ?>" name="<?php echo $this->get_field_name( 'showdate' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'showdate' ); ?>"><?php _e( 'Display Post Date?' ); ?></label></p>
        
        <?php
	}
	
	// The Widget
	public function widget( $args, $instance ) {
	$response = wp_remote_get( $instance['siteurl'] . '/wp-json/wp/v2/posts?per_page=' . $instance['numpost'] );
	// Make JSON date human readable
	
		
	if( is_wp_error( $response ) ) {
		return;
	}

	$posts = json_decode( wp_remote_retrieve_body( $response ) );

	if( empty( $posts ) ) {
		return;
	}
	
    echo $args['before_widget'];

	if( !empty( $instance['title'] ) ) {
		echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) . $args['after_title'];
	}

	if( !empty( $posts ) ) {
		echo '<ul>';
		foreach( $posts as $post ) {
		
			if ( ! empty( $post->excerpt->rendered ) ) :
			// Start Excerpt Limiter

		$the_excerpt = strip_tags($post->excerpt->rendered);
		
					$limit = null; $separator = null;
					// Set standard words limit
					if (is_null($limit)){
						$excerpt = explode(' ', $the_excerpt, $instance['excerpt_length']+1);
					} else {
						$excerpt = explode(' ', $the_excerpt, $limit);
					}

					// Set standard separator
					if (is_null($separator)){
						$separator = '...';
					}

					// Excerpt Generator
					if (count($excerpt)>=$limit) {
						array_pop($excerpt);
						$excerpt = implode(" ",$excerpt).$separator;
					} else {
						$excerpt = implode(" ",$excerpt);
					}   
					$excerpt = preg_replace('`\[[^\]]*\]`','',$excerpt);
				// End Excerpt Limiter
			endif;
			
			$jsondate = new DateTime( $post->date );
			$humandate = $jsondate->format("F d, Y");
			// Check if excerpt exists, if not, show nothing, otherwise ready to add to the featured image title tag
			$title_tag_excerpt = empty( strip_tags($post->excerpt->rendered ) ) ? '' : PHP_EOL . PHP_EOL . $excerpt;
			// Setup the title tag for links
			$link_title_tag = empty( $post->title->rendered ) ? '' : $post->title->rendered . $title_tag_excerpt;
			// Setup starting tag of the post link
			$post_link = '<a href="' . $post->link . '" title="'.$link_title_tag.'">';
			// Show the posts
			echo '<li class="mrp-post-widget">';
			if( isset( $instance['showtitle'] ) ) : ?>
				<?php echo $post_link; ?><div class="mrp-post-title"><h3><?php echo $post->title->rendered; ?></h3></div></a>
			<?php endif;
			if( isset( $instance['featimg'] ) ) : ?>
				<?php echo $post_link; ?><div class="mrp-feat-img"><img src ="<?php echo $post->swp_thumbnail; ?>"/></div></a>
			<?php endif;
			if( isset( $instance['excerpt'] ) ) : ?>
				<div class="mrp-widget-excerpt"><?php echo '<span class="mrp-excerpt">' . $excerpt . '</span>'; ?></div>
			<?php endif;
			if( isset( $instance['showdate'] ) ) : ?>
				<div class="mrp-widget-post-date"><?php echo $humandate; ?></div>
			<?php endif;
		}
		echo '</li></ul>';
	}

	echo $args['after_widget'];

		}
	}
} // class_exists( 'WP_Widget' )

// Widget Init
add_action( 'widgets_init', function(){ register_widget( 'RESTposts_widget' ); });