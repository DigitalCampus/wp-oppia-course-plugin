<?php
/*
Plugin Name: Oppia Course
Plugin URI: https://github.com/DigitalCampus/wp-oppia-course-plugin
Description: Plugin for displaying Digital Campus custom post types
Version: 0.4
Author: Digital Campus
Author URI: https://digital-campus.org/
License: GPLv2
*/

include_once( 'courselist_shortcode.php' );

add_action( 'init', 'create_oppia_course' );
add_action( 'init', 'register_shortcodes' );
function register_shortcodes() {
    add_shortcode( 'oppia-courses', 'courselist_shortcode' );
}

/* Create the custom post type */
function create_oppia_course() {
    register_post_type( 'oppia_course',
        array(
            'labels' => array(
                'name' => 'Oppia Courses',
                'singular_name' => 'Oppia Course',
                'add_new' => 'Add new',
                'add_new_item' => 'Add new Oppia Course',
                'edit' => 'Edit',
                'edit_item' => 'Edit course',
                'new_item' => 'New course',
                'view' => 'View',
                'view_item' => 'View course',
                'search_items' => 'Search courses',
                'not_found' => 'Not found',
                'not_found_in_trash' => 'Not found in trash',
                'parent' => 'Parent Oppia Course'
            ),
 
            'public' => true,
            'menu_position' => 15,
            'supports' => array( 'title', 'editor', 'comments', 'thumbnail'),
            'taxonomies' => array( '' ),
            'menu_icon' => plugins_url( 'images/image.png', __FILE__ ),
            'has_archive' => true
        )
    );
}

/**
 * Include VafPress Framework
 */
if (file_exists(dirname(__FILE__).'/vafpress/bootstrap.php')) {
    require_once( dirname(__FILE__).'/vafpress/bootstrap.php' );
}
// Load vafpress metaboxes
if (file_exists(dirname(__FILE__).'/metabox-init.php')) {
    require_once( dirname(__FILE__).'/metabox-init.php' );
}
// Load vafpress data
if (file_exists(dirname(__FILE__).'/data_sources.php')) {
    require_once( dirname(__FILE__).'/data_sources.php' );
}


/* Add template for custom post type */
add_filter( 'template_include', 'include_template_function', 1 );
function include_template_function( $template_path ) {
    if ( get_post_type() == 'oppia_course' ) {
        if ( is_single() ) {
            // checks if the file exists in the theme first,
            // otherwise serve the file from the plugin
            if ( $theme_file = locate_template( array ( 'single-oppia_course.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = plugin_dir_path( __FILE__ ) . '/single-oppia_course.php';
            }
        }
    }
    return $template_path;
}

?>
