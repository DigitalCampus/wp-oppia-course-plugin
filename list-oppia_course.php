<?php
wp_enqueue_style( 'my-style', plugins_url( '/css/style_oppia_course.css', __FILE__ ), false, '1.0', 'all' ); // Inside a plugin
?>

<div class="oppia_courses_list">
    <?php

    $args = array(
        'post_type'      => 'oppia_course',
        'post_status' => 'publish',
        'posts_per_page' => -1,
    	'orderby'=>'title',
    	'order'=>'asc'
        );
    // Query Out Database
    $aa_tablesQuery = new WP_Query( $args );
    // Calling the loop
    if ($aa_tablesQuery->have_posts()) : while ( $aa_tablesQuery->have_posts() ) : $aa_tablesQuery->the_post();
    ?>
    <div class="oppia_course">
        <div class="title">
            <h3><?php the_title(); ?></a></h3>
        </div>
        <div class="course_icon">
            <?php the_post_thumbnail(); ?>
        </div>
        <!-- Display the Course's content -->
        <div class="description">
            <?php the_content(); ?>
        </div>
        <div class="aa_table">
            <div class="aa_table_inner_wrap">
                <p>Preview in Moodle: </p>
                <div class="links">
                    <?php
                    // Get the object
                    // i.e. $aa_tbl_mb_val = vp_metabox('aa_tbl_mb');
                    $aa_tbl_mb_val = ( null !== vp_metabox('aa_tbl_mb') ) ? vp_metabox('aa_tbl_mb') : 0 ;
                    if( $aa_tbl_mb_val != 0 ){
                    // Get the meta array in object
                    // Undefined constant corrected
                    // i.e. $aa_tbl_group_val = $aa_tbl_mb_val->meta['table_group'];
                        $aa_tbl_group_val = ( null !== $aa_tbl_mb_val->meta['table_group'] ) ? $aa_tbl_mb_val->meta['table_group'] : 0 ;
                    }
                    if( $aa_tbl_group_val != 0 ){
                    
                    	$i = 0;
                        //Get the numbered array containing key value pairs in the meta array of obj
                        foreach ($aa_tbl_group_val as $aa_tbl_group_val_num) {
			                /**
			                * Swap the values with keys to get a new array with numbered values
			                * E.g. array { [0] => val1, [1] => val2}
			                */
			                $aa_tbl_group_val_num_pair = array_values($aa_tbl_group_val_num);
			                //Print associative array of key val pairs with Property at 0th element and Value at 1th element of each array
			                foreach ($aa_tbl_group_val_num_pair as $key => $val) {
			                    if($key == 0){
			                        $nombre = $val;
			                    }
			                //Value
			                    else{
			                //echo " <span class=\"aa_value\"><a href=". $val .">". $nombre ."</a></span></div>";
			                        echo "<a href=". $val .">". $nombre ."</a>";
			                        if ($i != count($aa_tbl_group_val)-1 ){
			                        	echo " | ";
			                        }
			                    }
			                } // end foreach
			                $i++;
               			} // end foreach
                	} // End if
                ?>
            </div>
        </div>
        
<?php endwhile; endif; // END if and while for WordPress Loop ?>
