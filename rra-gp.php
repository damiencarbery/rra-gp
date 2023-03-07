<?php
/*
Plugin Name: Riverwood RA using GeneratePress
Plugin URI: http://www.riverwoodres.com
Description: Customise GeneratePress theme for Riverwood Residents Association.
Version: 0.1
Author: Damien Carbery
Author URI: http://www.damiencarbery.com
*/


// Remove archive title (to match Genesis theme site).
function rra_remove_archive_title() {
	remove_action( 'generate_archive_title', 'generate_archive_title' );
}
add_action( 'generate_before_main_content', 'rra_remove_archive_title' );


// First full content of post, and excerpts for rest.
function rra_full_content_first_post_category_pages( $show_excerpt ) {
	global $wp_query;
	
	if (is_paged()) {
		return $show_excerpt;
	}

	if( is_category() || is_front_page() ) {
		if ($wp_query->current_post == 0) {
			return false;
		}
	}

	return $show_excerpt;
}
add_filter( 'generate_show_excerpt', 'rra_full_content_first_post_category_pages' );


// Display date and categories as header entry meta.
function rra_header_entry_meta_items( $items ) {
	return array( 'date', 'categories' );
}
add_filter( 'generate_header_entry_meta_items', 'rra_header_entry_meta_items' );


// Insert the 'Posted on' inside the <span>.
function rra_category_meta_list_output( $cat_list_markup ) {
	return str_replace( '<span class="cat-links">', '| <span class="cat-links">Posted in ', $cat_list_markup );
}
add_filter( 'generate_category_list_output', 'rra_category_meta_list_output' );


// Move featured image to after single post title. Also need to add margin-top CSS to add space between title and featured image.
add_action( 'generate_before_content', 'rra_move_featured_image_to_after_title', 5 );
function rra_move_featured_image_to_after_title() {
	remove_action( 'generate_before_content', 'generate_featured_page_header_inside_single' );
	add_action( 'generate_after_entry_header', 'generate_featured_page_header_inside_single' );
}


function rra_remove_footer_meta() {
	remove_action( 'generate_after_entry_content', 'generate_footer_meta' );
}
add_action( 'generate_after_entry_title', 'rra_remove_footer_meta' );


function rra_footer_creds() {
	echo sprintf( '<p>Copyright &copy; 2010-%d <a href="%s">%s</a> &middot; All Rights Reserved.</p>', date( 'Y' ), get_home_url(), get_bloginfo('name') );
}


function rra_replace_default_footer_info() {
	remove_action( 'generate_credits', 'generate_add_footer_info' );
	add_action( 'generate_credits', 'rra_footer_creds' );
}
add_action( 'generate_before_copyright', 'rra_replace_default_footer_info' );