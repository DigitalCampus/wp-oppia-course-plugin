<?php get_header(); ?>
<?php
// Start the loop.
while ( have_posts() ) : the_post();
endwhile;
?>
<div id="primary" class="span8">
    <h2><a href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
    <!-- Display the Post's content in a div box. -->
    <?php the_post_thumbnail(); ?>
    <div class="entry2" style="margin-top: 30px;">
        <?php the_content(); ?>
    </div>
    <?php
/**
* Tables loop
*/
/**
* Loop
*/
$args = array(
    'post_type'      => 'oppia_course',
    'posts_per_page' => -1
    );
// Query Out Database
$aa_tablesQuery = new WP_Query( $args );
// Calling the loop
if ($aa_tablesQuery->have_posts()) : while (have_posts() ) :the_post();
?>
<div class="aa_table">
    <div class="aa_table_inner_wrap">
        <br></br>
        <h4><?php echo "Versions" ?></h4>
        <div class="aa_tbl_prp_out">
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
            //Get the numbered array containing key value pairs in the meta array of obj
                foreach ($aa_tbl_group_val as $aa_tbl_group_val_num) {
            /**
            * Swap the values with keys to get a new array with numbered values
            * E.g. array { [0] => val1, [1] => val2}
            */
            $aa_tbl_group_val_num_pair = array_values($aa_tbl_group_val_num);
            //Print associative array of key val pairs with Property at 0th element and Value at 1th element of each array
            foreach ($aa_tbl_group_val_num_pair as $key => $val) {
            //Property
                if($key == 0){
                    $nombre = $val;
                    echo "<div class=\"aa_table__prprty_value\">";
                }
                //Value
                else{
                    echo " <span class=\"aa_value\"><a href=". $val .">". $nombre ."</a></span></div>";
                }
                } // end foreach
                } // end foreach
                } // End if
                ?>
            </div>
        </div>
    </div>
    <!-- /.aa_table -->
    <br><br>
</div>
<?php endwhile; endif; // END if and while for WordPress Loop ?>
<?php get_sidebar(); ?>
<br>
<br>
<?php get_footer(); ?>