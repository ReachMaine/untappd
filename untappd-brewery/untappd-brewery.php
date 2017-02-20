<?php
/*
Plugin Name: Untappd Brewery Plugin
Description: Plugin to utilize Untappd's API for a brewery.  Useful for brewery Owners website.
Version: 0.1
Author: zig
*/

include('untappd-brewery-class.php');
include('untappd-brewery-output.php');
function utb_admin() {
	include('untappd-brewery-admin.php');
}

function utb_admin_actions() {
    add_options_page("Untappd Brewery Options", "Untappd Brewery", "manage_options", "utb_brewery", "utb_admin");
}

//********Get API settings for  ***********

function utb_get_api_settings()
{
    $utbclientid = get_option('utb_clientid');
    $utbsecret = get_option('utb_secret');
    $utbbrewery = get_option('utb_breweryid');
    $config = array(
        'clientId'     => $utbclientid,
        'clientSecret' => $utbsecret,
        'breweryid' => $utbbrewery,
        'redirectUri'  => $utbredirecturi
    );
    return $config;
}
//******************* Shortcode Functions *****************\\
// Brewery
function utb_brewery_shortcode($atts) {
    extract(shortcode_atts(array(
      "limit" => ''
    ), $atts));
    $id = get_option('utb_breweryid');
    $feedtype = 'breweryBeers';
    echo utb_output($id,$feedtype,$limit);
}


// styling

function DR_add_stylesheet() {
    // Respects SSL, Style.css is relative to the current file
    wp_register_style( 'utb-style', plugins_url('css/style.css', __FILE__) );
    wp_enqueue_style( 'utb-style' );
}
/*  basic hooks */
add_shortcode("utb_brewery", "utb_brewery_shortcode");

add_action('admin_menu', 'utb_admin_actions');
add_action( 'wp_enqueue_scripts', 'DR_add_stylesheet' );
