<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/*

Plugin Name: Key Points Short Description

Plugin URI: https://mikehosker.net/key-points-short-description/

Description: Plugin to replace WooCommerce product short descriptions with key points.

Version: 1.0

Author: Mike Hosker

Author URI: https://mikehosker.net/

Text Domain: kpsd

*/

// Include admin code

include (plugin_dir_path( __FILE__ ) . 'admin.php');

// Include frontend code

include (plugin_dir_path( __FILE__ ) . 'frontend.php');

?>
