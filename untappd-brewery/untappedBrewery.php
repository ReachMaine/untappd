<?php
/*
Plugin Name: untappedBrewery
Description: Plugin to utilize Untappd's API for a brewery.  Useful for brewery Owners website.
Version: 0.1
Author: zig
*/

include('untappedBrewery-class.php');
function utb_admin() {
	include('untappedBrewery-admin.php');
}

function utb_admin_actions() {
    add_options_page("Untappd Brewery Options", "Untappd Brewery", "manage_options", "utb_brewery", "utb_admin");
}


add_action('admin_menu', 'utb_admin_actions');
