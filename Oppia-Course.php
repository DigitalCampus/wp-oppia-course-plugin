<?php
/*
Plugin Name: Oppia Course
Plugin URI: https://www.oppia.org/splash
Description: Permite generar un curso de Oppia
Version: 0.3
Author: Chaotic-kingdoms
Author URI: http://www.chaotic-kingdoms.com
License: GPLv2
*/
/*La funcion de a continuación llama siempre a la función crete_movie_review siempre que se genera la página*/
add_action( 'init', 'create_oppia_course' );

/*Esta función hace la mayoría del trabajo por nosotros
Prepara una nueva sección. Estsa función toma dos argumentos, 
    el nombre
    un array con las propiedades del tipo:
        name indica el nombre que se mostrará en el dashboard

Otros argumentos importantes son:
    public => true, determina la visibilidad en el panel.
    menu_position pues la posición.*/
function create_oppia_course() {
    register_post_type( 'oppia_course',
        array(
            'labels' => array(
                'name' => 'Oppia Course',
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
 *
 * Include VafPress Framework
 *
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


/*Hasta este momento podemos guardar entradas, ahora tenemos que hacer que se muestren correctamente cuando las publicamos*/
add_filter( 'template_include', 'include_template_function', 1 );
//Simplemente esta función llama a un php del formato sinle-(post-type-name).php que es donde esta el esquema
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

//////////////////////TERCER TUTORIAL
//En este caso añadimos los temas
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
                add_filter(
                    'page_attributes_dropdown_pages_args',
                     array( $this, 'register_project_templates' ) 
                );


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
                        'single-oppia_course-todos.php'     => 'OppiaCourse',
                );
                
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

                global $post;

                if (!isset($this->templates[get_post_meta( 
                    $post->ID, '_wp_page_template', true 
                )] ) ) {
                    
                        return $template;
                        
                } 

                $file = plugin_dir_path(__FILE__). get_post_meta( 
                    $post->ID, '_wp_page_template', true 
                );
                
                // Just to be safe, we check if the file exist first
                if( file_exists( $file ) ) {
                        return $file;
                } 
                else { echo $file; }

                return $template;

        } 


} 

add_action( 'plugins_loaded', array( 'PageTemplater', 'get_instance' ) );


///////////////////////AÑADIMOS EL CSS
?>
