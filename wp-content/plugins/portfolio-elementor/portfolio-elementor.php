<?php
/*
Plugin Name: Portfolio for Elementor 
Plugin URI: https://wppug.com/creative-portfolio-plugin-demo/
Description: This plugin extend Elementor by adding the Portfolio functionality for free!
Author: WpPug
Version: 1.0.4
Author URI: http://wppug.com
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists('ELPT_portfolio_Post_Types') ) {
	/*
	 * Elementor Portfolio
	 */
	require ('portfolio/elementor-portfolio.php');
	/*
	 * Plugin Options
	 */
	require ('panel.php');
}