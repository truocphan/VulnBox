<?php

global $post;

$args = array(
	'meta_query' => array(
		'relation' => 'OR',
		array(
			'key'     => 'google_classroom_auditory',
			'value'   => $post->ID,
			'compare' => 'LIKE',
		),
	),
);

// Create the WP_User_Query object
$wp_user_query = new WP_User_Query( $args );

// Get the results
$authors = $wp_user_query->get_results();

// Check for results
if ( ! empty( $authors ) ) : ?>
	<ul>
		<?php foreach ( $authors as $author ) : ?>
			<li>
				<a href="<?php echo esc_url( get_edit_user_link( $author->ID ) ); ?>#google_classroom_auditory" target="_blank">
					<i class="lnr lnricons-pencil"></i>
					<?php echo esc_html( $author->data->user_email ); ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>
