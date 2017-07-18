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

add_action( 'init', 'create_oppia_course' );

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
            if ( $theme_file = locate_template( array ( 'single_oppia_course.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = plugin_dir_path( __FILE__ ) . '/single-oppia_course.php';
            }
        }
    }
    return $template_path;
}

//Add custom template
class PageTemplater {

        /**
         * A Unique Identifier
         */
         protected $plugin_slug;

        /**
         * A reference to an instance of this class.
         */
        private static $instance;

        /**
         * The array of templates that this plugin tracks.
         */
        protected $templates;


        /**
         * Returns an instance of this class. 
         */
        public static function get_instance() {

                if( null == self::$instance ) {
                        self::$instance = new PageTemplater();
                } 

                return self::$instance;

        } 

        /**
         * Initializes the plugin by setting filters and administration functions.
         */
        private function __construct() {

                $this->templates = array();


                // Add a filter to the attributes metabox to inject template into the cache.
                if ( version_compare( floatval( get_bloginfo( 'version' ) ), '4.7', '<' ) ) {
                    // 4.6 and older
                    add_filter(
                        'page_attributes_dropdown_pages_args',
                        array( $this, 'register_project_templates' )
                    );
                } else {
                    // Add a filter to the wp 4.7 version attributes metabox
                    add_filter(
                        'theme_page_templates', array( $this, 'add_new_template' )
                    );
                }


                // Add a filter to the save post to inject out template into the page cache
                add_filter(
                    'wp_insert_post_data', 
                    array( $this, 'register_project_templates' ) 
                );

                // Add a filter to the template include to determine if the page has our 
                // template assigned and return it's path
                add_filter(
                    'template_include', 
                    array( $this, 'view_project_template') 
                );

                // Add your templates to this array.
                $this->templates = array(
                        'list-oppia_course.php' => 'Oppia: Course List',
                );
                
        } 

        /**
         * Adds our template to the page dropdown for v4.7+
         *
         */
        public function add_new_template( $posts_templates ) {
            $posts_templates = array_merge( $posts_templates, $this->templates );
            return $posts_templates;
        }


        /**
         * Adds our template to the pages cache in order to trick WordPress
         * into thinking the template file exists where it doens't really exist.
         *
         */
        public function register_project_templates( $atts ) {

                // Create the key used for the themes cache
                $cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );

                // Retrieve the cache list. 
                // If it doesn't exist, or it's empty prepare an array
                $templates = wp_get_theme()->get_page_templates();
                if ( empty( $templates ) ) {
                        $templates = array();
                } 

                // New cache, therefore remove the old one
                wp_cache_delete( $cache_key , 'themes');

                // Now add our template to the list of templates by merging our templates
                // with the existing templates array from the cache.
                $templates = array_merge( $templates, $this->templates );

                // Add the modified cache to allow WordPress to pick it up for listing
                // available templates
                wp_cache_add( $cache_key, $templates, 'themes', 1800 );

                return $atts;

        } 

        /**
         * Checks if the template is assigned to the page
         */
        public function view_project_template( $template ) {

                // Get global post
                global $post;
                // Return template if post is empty
                if ( ! $post ) {
                    return $template;
                }
                // Return default template if we don't have a custom one defined
                if ( ! isset( $this->templates[get_post_meta( 
                    $post->ID, '_wp_page_template', true 
                )] ) ) {
                    return $template;
                } 
                $file = plugin_dir_path( __FILE__ ). get_post_meta( 
                    $post->ID, '_wp_page_template', true
                );
                // Just to be safe, we check if the file exist first
                if ( file_exists( $file ) ) {
                    return $file;
                } else {
                    echo $file;
                }
                // Return template
                return $template;

        } 


} 

add_action( 'plugins_loaded', array( 'PageTemplater', 'get_instance' ) );

?>
