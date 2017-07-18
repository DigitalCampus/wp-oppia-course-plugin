<?php

    function courselist_shortcode(){

        if ( $theme_file = locate_template( array ( 'list-oppia_course.php' ) ) ) {
            $template_path = $theme_file;
        } else {
            $template_path = plugin_dir_path( __FILE__ ) . '/list-oppia_course.php';
        }

        ob_start();
        include($template_path);
        wp_reset_postdata();
        return ob_get_clean();
    }
?>