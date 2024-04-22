<?php
$id = get_the_ID();
$related_option = STM_LMS_Options::get_option( 'related_option', 'by_category' );
$args = array(
    'post_type' => 'stm-courses',
    'posts_per_page' => 3,
    'per_row' => 3,
    'post__not_in' => array( $id )
);

if( $related_option === 'by_category' ) {
    $categories = wp_get_post_terms( $id, 'stm_lms_course_taxonomy' );
    $terms = array();
    foreach( $categories as $category ) {
        $terms[] = $category->term_id;
    }

    if( !empty( $terms ) ) {
        $args[ 'tax_query' ] = array(
            array(
                'taxonomy' => 'stm_lms_course_taxonomy',
                'field' => 'id',
                'terms' => $terms
            )
        );
    }
}
elseif( $related_option === 'by_author' ) {
    $post = get_post( $id );
    if( !empty( $post ) ) {
        $author_id = $post->post_author;
        $author_array = array(
            'author' => $author_id
        );
        $args = array_merge( $args, $author_array );
    }
}
else if( $related_option === 'by_level' ) {
    $level = get_post_meta( $id, 'level', true );
    $args[ 'meta_query' ] = array(
        [
            'key' => 'level',
            'value' => $level
        ],
    );
}

$q = new WP_Query( $args );
if( $q->have_posts() ):
    ?>
    <div class="stm_lms_related_courses">
        <h2><?php esc_html_e( 'Related Courses', 'masterstudy-lms-learning-management-system' ); ?></h2>
        <?php
        STM_LMS_Templates::show_lms_template( 'courses/grid', array( 'args' => $args ) );
        ?>
    </div>
<?php endif;
wp_reset_postdata();
