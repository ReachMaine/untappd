<?php
/******* Custom Post type for Beers ***********/
add_action ('init', 'create_beer_posttype');
if (!function_exists('create_beer_posttype')) {
	function create_beer_posttype() {

		register_post_type( 'utb_beer',
		    array (
		      'labels' => array(
		        'name' => __( 'Ales', 'utb_utbdom' ),
		        'singular_name' => __( 'Ale', 'utb_utbdom' )
		    ),
		      'taxonomies' => array('category', 'post_tag'),
		      'public' => true,
		      'has_archive' => true,
		      'supports' => array( 'title', 'editor', 'excerpt', 'custom-fields', 'thumbnail' ),
		      'rewrite' => array('slug' => 'ale'),
		)   );

	}
}

?>
