<?php
/*
 * Plugin Name: Enquiry Cart
 * Description: Plugin for displaying products and add enquiry form
 * Plugin URL: http://www.ssiexpert.com/
 * Version: 0.1
 * Author: SSiexpert
 * Author URI: http://www.ssiexpert.com/
*/
define( 'MY_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'MY_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define('NO_IMG_URL', MY_PLUGIN_URL."images/No-product-image.jpg");
require_once MY_PLUGIN_PATH.'includes/config.php';
require_once MY_PLUGIN_PATH.'featured-galleries/featured-galleries.php';
require_once MY_PLUGIN_PATH.'categories-images.php';
require_once MY_PLUGIN_PATH.'includes/functions.php';
add_filter('fg_post_types', 'add_featured_galleries_to_ctp' );
add_theme_support( 'post-thumbnails' );
add_theme_support( 'post-thumbnails', array( 'post', 'page', 'product' ) );
add_action('init','register_session');
add_action( 'init', 'register_order_posttype' );
add_action('admin_menu', 'product_admin_actions');
add_action( 'admin_init','client_file');
add_action('admin_head', 'remove_parmalink');
add_filter( 'post_row_actions', 'remove_row_actions', 10, 1 );
add_action( 'wp_enqueue_scripts', 'register_frontent_style' );
add_filter('single_template', 'custom_single_template');
add_filter( 'template_include', 'category_template' );
add_action("admin_init", "add_meta_dy");
add_action( 'admin_init', 'hide_page_attribute' );
add_action('save_post', 'save_product');
add_filter('manage_product-enquiry_posts_columns', 'ST4_columns_head');
add_action('manage_product-enquiry_posts_custom_column', 'ST4_columns_content', 10, 2);
add_filter('page_template', 'catch_plugin_template');
add_action( 'wp_ajax_update_order', 'my_action_callback' );//use it for admin meta ordering only call from admin
add_action( 'wp_ajax_update_cart', 'cart_function' ); // remove cart item it can be call from admin
add_action( 'wp_ajax_nopriv_update_cart', 'cart_function' ); // remove cart item it can be call from without admin
add_action( 'wp_ajax_total_items', 'get_total_number' ); //get total cart items only admin
add_action( 'wp_ajax_nopriv_total_items', 'get_total_number' ); //get total cart items without admin
add_action('wp_head', 'myplugin_ajaxurl');
register_activation_hook( __FILE__, 'order_activate' );
register_deactivation_hook( __FILE__, 'order_deactivate' );
