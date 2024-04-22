<?php
/**
 * @var $post_id
 * @var $post_meta
 */

$ids = json_decode( get_post_meta( $post_id, $post_meta, true ) );

if ( ! empty( $ids ) ) {
	$attachments = get_posts(
		array(
			'post_type' => 'attachment',
			'include'   => $ids,
			'order'     => 'ASC',
		)
	);

	if ( is_array( $attachments ) ) : ?>
		<div class="stm_lms_downloadable_content__files" id="stm_lms_downloadable_content__files">
			<?php
			foreach ( $attachments as $attachment ) :
				$file            = get_attached_file( $attachment->ID );
				$file_size       = filesize( $file );
				$file_size       = $file_size / 1024;
				$file_size_label = 'kb';

				if ( $file_size > 1000 ) {
					$file_size       = $file_size / 1024;
					$file_size_label = 'mB';
				}

				STM_LMS_Templates::show_lms_template(
					'global/file',
					array(
						'title'          => $attachment->post_title,
						'filename'       => basename( $file ),
						'ext'            => pathinfo( $file, PATHINFO_EXTENSION ),
						'filesize'       => $file_size,
						'filesize_label' => $file_size_label,
						'url'            => wp_get_attachment_url( $attachment->ID ),
					)
				);

			endforeach;
			?>
		</div>
		<?php
	endif;
}
