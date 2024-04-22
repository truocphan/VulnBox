<?php

/**
 * @var int $id
 * @var object $content
 * @var array $settings
 * @var boolean $dark_mode
 */

wp_enqueue_style( 'masterstudy-wp-editor' );
wp_enqueue_script( 'masterstudy-wp-editor' );

$options     = get_option( 'stm_lms_settings' );
$theme_fonts = $options['course_player_theme_fonts'] ?? false;

wp_localize_script(
	'masterstudy-wp-editor',
	'data',
	array(
		'editor_id'   => $id,
		'dark_mode'   => $dark_mode,
		'theme_fonts' => $theme_fonts,
		'translate'   => array(
			'word' => esc_html__( 'words', 'masterstudy-lms-learning-management-system' ),
		),
	)
);
?>

<div class="masterstudy-wp-editor <?php echo esc_attr( $dark_mode ? 'masterstudy-wp-editor_dark-mode' : '' ); ?>">
	<?php wp_editor( $content, $id, $settings ); ?>
</div>
