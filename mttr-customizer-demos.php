<?php
/** --------------------------------------------------------
 *        __ __  __
 *      /  /   /   /     __/__/__
 *      \ /   /   /  __   /  /  __  (/__
 *       /   /   / /  /  /  /  /__) /  /
 *      /   /   / (__/__/_ /__/____/  /_/
 *              \
 *                SOLUTIONS
 *
 * Plugin Name: Customizer Demos
 * Author: Matter Solutions
 * Author URI: https://mattersolutions.com.au
 *Description: Customise all the things
 ---------------------------------------------------------- */

function mttr_customizer_demos_init() {

	require_once( 'inc/phone-number-widget.php' );

	$mttr_phone_number_init = new mttr_phone_number_init;

	/*require_once( 'inc/css-colour.php' );

	$mttr_css_link_colour = new mttr_css_colour_init;*/
	
}

add_action( 'plugins_loaded', 'mttr_customizer_demos_init' );


// Technically we don't need this as twentysixteen already supports it
function mttr_customizer_demos_theme_support() {

	add_theme_support( 'customize-selective-refresh-widgets' );

}

add_action( 'after_setup_theme', 'mttr_customizer_demos_theme_support' );