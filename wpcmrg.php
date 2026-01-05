<?php
/***
 * Plugin Name: WP Content Manager Ranjit Galsar
 * Plugin URI: https://github.com/ranjitgalsar/wp-content-manager-ranjit-galsar
 * Description: WP Content Manager
 * Version: 1.0.0
 * Author: Ranjit Galsar
 * Author URI: https://github.com/ranjitgalsar
 * License: GPLv2 or later
 * Text Domain: wpcmrg
 */

if(!defined('ABSPATH')){
    exit;
}

define( 'WPCMRG_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WPCMRG_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'WPCMRG_VERSION', '1.0.0' );
define( 'WPCMRG__MINIMUM_WP_VERSION', '5.8' );

require_once( WPCMRG_PLUGIN_DIR . 'class.wpcmrg.php' ); 


add_action( 'init', array( 'WPCMRG', 'init' ) );
add_action( 'add_meta_boxes', array( 'WPCMRG', 'add_meta_boxes' ) );
add_action('admin_head', array( 'WPCMRG', 'admin_styles' ));
add_action('save_post', array( 'WPCMRG', 'save_promo_block' ));
add_action('wp_enqueue_scripts', array( 'WPCMRG', 'enque_styles' ));
add_action('wp_ajax_clear_promo_blocks_cache', array( 'WPCMRG', 'clear_promo_blocks_cache' ));
add_action('wp_ajax_nopriv_clear_promo_blocks_cache', array( 'WPCMRG', 'clear_promo_blocks_cache' ));

add_action('rest_api_init', array( 'WPCMRG', 'register_rest_routes' ));
add_action("admin_menu", array( 'WPCMRG', 'hide_promo_blocks' ));

register_activation_hook( __FILE__, array( 'WPCMRG', 'plugin_activation' ) );
register_deactivation_hook( __FILE__, array( 'WPCMRG', 'plugin_deactivation' ) );
