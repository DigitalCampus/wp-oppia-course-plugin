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
        <div class="oppia_card">
        <div class="course_icon">
            <?php the_post_thumbnail(); ?>
        </div>

        <div class="title">
            <?php the_title(); ?>
        </div>
        
        <!-- Display the Course's content -->
        <div class="description">
            <?php the_content(); ?>
        </div><?php
                    // Get the object
                    $versions_mb = ( null !== vp_metabox('course_version_mb') ) ? vp_metabox('course_version_mb') : 0;
                    if($versions_mb != 0){
                        $versions = ( null !== $versions_mb->meta['table_group'] ) ? $versions_mb->meta['table_group'] : 0 ;
                    }
                    if( ($versions != 0) && (count($versions)>0) ){ 
                        //Check that the first one is not empty
                        if (array_values($versions[0])[0] != ''){ ?><div class="links">
                                Preview in Moodle:<br>
                    <?php
                            //Get the numbered array containing key value pairs in the meta array of obj
                            foreach ($versions as $version) {
                                $values = array_values($version);
                                $label_version = $values[0];
                                $href_version = $values[1];
                            ?>
                            <a class="oppia_btn" href="<?= $href_version ?>"> <?= $label_version ?></a><?php
                            
                            } ?>
                            </div><?php }
                    } // end foreach
                ?></div></div>
<?php endwhile; endif; // END if and while for WordPress Loop ?>
</div>